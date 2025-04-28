<?php

namespace App\Repositories\Url;

use App\Repositories\Url\DTO\UrlObjectResponseDTO;

interface UrlRepositoryInterface
{
    public function getLastId():int;

    public function saveUrl(string $url, string $code): void;

    public function isCodeExists(string $code): bool;

    public function findByCode(string $code):?UrlObjectResponseDTO ;

    public function increaseCounter(string $code);
}
