<?php

namespace App\Repositories\TransferMoney;

interface WalletRepoInterface
{
    public function getUserBalanceWithLock(int $userId): int;

    public function decreaseUserBalance(int $userId, int $balance): void;

    public function increaseUserBalance(int $userId, int $balance): void;
}
