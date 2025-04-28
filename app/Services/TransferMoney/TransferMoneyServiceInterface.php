<?php

namespace App\Services\TransferMoney;

use App\DTO\TransferDetailRequestDTO;
use App\DTO\TransferDetailResponseDTO;

interface TransferMoneyServiceInterface
{
    public function transfer(TransferDetailRequestDTO $transferDetail): TransferDetailResponseDTO;
}
