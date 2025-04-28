<?php

namespace App\Repositories\TransferMoney;

use App\DTO\TransactionCreateDTO;
use App\Models\Transaction;

class TransactionRepo implements TransactionRepoInterface
{
    public function add(TransactionCreateDTO $DTO): void
    {
        Transaction::query()
            ->create([
                'user_id' => $DTO->getUserId(),
                'amount' => $DTO->getAmount(),
                'type' => $DTO->getType(),
            ]);
    }
}
