<?php

namespace App\DTO;

class TransferDetailResponseDTO
{
    private bool $status;

    private ?int $fee = null;

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function setFee(?int $fee): self
    {
        $this->fee = $fee;

        return $this;
    }

    public function getFee(): ?int
    {
        return $this->fee;
    }
}
