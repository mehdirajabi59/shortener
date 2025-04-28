<?php

namespace App\Http\Controllers\API\V1;

use App\Services\DTO\SaveUrlRequestDTO;
use App\Services\UrlService;

class UrlController
{

    public function __construct(private readonly UrlService $service)
    {
    }

    public function store(CreateUrlRequest $request)
    {
        $url = $request->validated()['url'];
        // 1s1kdn - 39^5

        $this->service->saveUrl(
            (new SaveUrlRequestDTO())
            ->setUrl($url)
        );

        return response([
            'message' => 'ok'
        ], Response::HTTP_CREATED);
    }

    public function g2o(string $code)
    {
        $url = $this->g2o($code);

        return redirect()->to($url);
    }
}
