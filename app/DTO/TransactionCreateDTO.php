<?php

namespace App\DTO;

use App\Enums\TransactionTypeEnum;

class TransactionCreateDTO
{
    private int $userId;

    private TransactionTypeEnum $type;

    private int $amount;

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setType(TransactionTypeEnum $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): TransactionTypeEnum
    {
        return $this->type;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
