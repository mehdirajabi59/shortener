<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    echo(__('exceptions.'.\App\Exceptions\UserBalanceNotEnoughException::class));

    dd('');
    return view('welcome');
});
