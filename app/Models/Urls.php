<?php

namespace App\Models;

class Urls extends Model
{
    protected $fillable = [
        'url', 'code', 'expired_at'
    ];

    public function cast()
    {
        return [
            'expired_at' => 'datetime'
        ];
    }
}
