<?php

use Illuminate\Support\Facades\Route;


Route::prefix('/urls')->group(function (){
    Route::post('/', [\App\Http\Controllers\API\V1\UrlController::class, 'store']);
    Route::get('g2o/{code}', [\App\Http\Controllers\API\V1\UrlController::class, 'g2o']);
});
