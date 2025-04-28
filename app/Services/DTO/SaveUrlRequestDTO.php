<?php

namespace App\Services\DTO;

class SaveUrlRequestDTO
{
    private string $url;

    public function setUrl(string $url): SaveUrlRequestDTO
    {
        $this->url = $url;
        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
