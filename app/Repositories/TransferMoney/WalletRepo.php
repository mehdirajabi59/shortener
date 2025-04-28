<?php

namespace App\Repositories\TransferMoney;

use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class WalletRepo implements WalletRepoInterface
{
    public function getUserBalanceWithLock(int $userId): int
    {
        $wallet = Wallet::query()
            ->lockForUpdate() // This locks the row to prevent other processes from modifying it
            ->firstOrCreate(
                ['user_id' => $userId],
                ['balance' => 0]
            );

        return $wallet->balance;
    }

    public function decreaseUserBalance(int $userId, int $balance): void
    {
        Wallet::query()
            ->where('user_id', $userId)
            ->lockForUpdate()
            ->decrement('balance', $balance);
    }

    public function increaseUserBalance(int $userId, int $balance): void
    {
        Wallet::query()
            ->where('user_id', $userId)
            ->lockForUpdate()
            ->increment('balance', $balance);
    }
}
