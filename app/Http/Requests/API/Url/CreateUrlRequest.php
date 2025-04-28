<?php

namespace App\Http\Requests\API\Url;

class CreateUrlRequest
{
    public function rules()
    {
        return [
            'url' => ['required', 'url']
        ];
    }
}
