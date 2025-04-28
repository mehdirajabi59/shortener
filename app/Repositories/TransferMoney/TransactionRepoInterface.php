<?php

namespace App\Repositories\TransferMoney;

use App\DTO\TransactionCreateDTO;

interface TransactionRepoInterface
{
    public function add(TransactionCreateDTO $DTO): void;
}
