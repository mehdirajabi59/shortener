<?php

use Illuminate\Support\Facades\Route;

Route::post(
    '/wallets/transfer-money',
    [\App\Http\Controllers\API\Wallet\TransferMoneyController::class, '__invoke']
)
    ->middleware(\App\Http\Middleware\DecodeHashIdMiddleware::class.':to')
    ->name('wallets.transfer-money');
