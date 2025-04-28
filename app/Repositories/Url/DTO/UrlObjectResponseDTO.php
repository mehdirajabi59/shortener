<?php

namespace App\Repositories\Url\DTO;

class UrlObjectResponseDTO
{
    private string $code;
    private string $url;
    private Carbon $expiredAt;

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): UrlObjectResponseDTO
    {
        $this->code = $code;
        return $this;
    }

    public function setUrl(string $url): UrlObjectResponseDTO
    {
        $this->url = $url;
        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setExpiredAt(Carbon $expiredAt): UrlObjectResponseDTO
    {
        $this->expiredAt = $expiredAt;
        return $this;
    }

    public function getExpiredAt(): Carbon
    {
        return $this->expiredAt;
    }
}
