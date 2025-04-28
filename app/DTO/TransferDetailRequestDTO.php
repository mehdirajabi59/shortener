<?php

namespace App\DTO;

class TransferDetailRequestDTO
{
    private int $userSourceId;

    private int $userDestId;

    private int $amount;

    public function setUserSourceId(int $userSourceId): self
    {
        $this->userSourceId = $userSourceId;

        return $this;
    }

    public function getUserSourceId(): int
    {
        return $this->userSourceId;
    }

    public function setUserDestId(int $userDestId): self
    {
        $this->userDestId = $userDestId;

        return $this;
    }

    public function getUserDestId(): int
    {
        return $this->userDestId;
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
