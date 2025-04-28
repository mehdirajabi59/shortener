<?php

namespace App\Services;

use App\Exceptions\CodeNotFoundException;
use App\Repositories\Url\UrlRepositoryInterface;
use App\Services\DTO\SaveUrlRequestDTO;

class UrlService
{

    public function __construct(private readonly UrlRepositoryInterface $repository)
    {
    }

    public function saveUrl(SaveUrlRequestDTO $requestDTO)
    {
        //Get Last Id
        $lastId = $this->repository->getLastId();
        //Generate code
        //Append last id
        do{
            $code = $this->generateCode($lastId + 1);

        }while($this->repository->isCodeExists($code));

        $this->repository->saveUrl($requestDTO->getUrl(), $code);
        // Save Url
    }

    private function generateCode(int $id) : string
    {
        $randomString = Str::random(5);

        $randomPosition = rand(0, 5);
        if($randomPosition === 0){
            return $id .$randomString;
        }

        return substr($randomString, $randomPosition) . $id . substr($randomString, $randomPosition + 1);
    }


    public function getUrl(string $code): string
    {
        //Find code
        $urlObjectDTO = $this->repository->findByCode($code);
        if(is_null($urlObjectDTO)){
            throw new CodeNotFoundException;
        }
        //Update counter
        $this->repository->increaseCounter($code);
        //return url
        return $urlObjectDTO->getUrl();
    }
}
