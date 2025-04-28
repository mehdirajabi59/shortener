<?php

namespace App\Providers;

use App\Repositories\TransferMoney\TransactionRepo;
use App\Repositories\TransferMoney\TransactionRepoInterface;
use App\Repositories\TransferMoney\WalletRepo;
use App\Repositories\TransferMoney\WalletRepoInterface;
use App\Services\TransferMoney\TransferMoneyService;
use App\Services\TransferMoney\TransferMoneyServiceInterface;
use Illuminate\Support\ServiceProvider;

class TransferMoneyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TransferMoneyServiceInterface::class, TransferMoneyService::class);
        $this->app->bind(TransactionRepoInterface::class, TransactionRepo::class);
        $this->app->bind(WalletRepoInterface::class, WalletRepo::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
