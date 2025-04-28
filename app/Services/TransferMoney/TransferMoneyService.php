<?php

namespace App\Services\TransferMoney;

use App\DTO\TransactionCreateDTO;
use App\DTO\TransferDetailRequestDTO;
use App\DTO\TransferDetailResponseDTO;
use App\Enums\TransactionTypeEnum;
use App\Exceptions\UserBalanceNotEnoughException;
use App\Repositories\TransferMoney\TransactionRepoInterface;
use App\Repositories\TransferMoney\WalletRepoInterface;
use Illuminate\Support\Facades\DB;
use Throwable;

class TransferMoneyService implements TransferMoneyServiceInterface
{
    public function __construct(private WalletRepoInterface $transferMoneyRepo, private TransactionRepoInterface $transactionRepo) {}

    /** Transfer money with two wallets
     * @param TransferDetailRequestDTO $transferDetail
     * @return TransferDetailResponseDTO
     * @throws UserBalanceNotEnoughException
     */
    public function transfer(TransferDetailRequestDTO $transferDetail): TransferDetailResponseDTO
    {
        $fee = config('transaction.fee');
        //calculate need balance
        $needBalance = $transferDetail->getAmount() + $fee;
        //Check User Destination has enough balance + fee
        if ($this->transferMoneyRepo->getUserBalanceWithLock($transferDetail->getUserSourceId()) < $needBalance) {
            throw (new UserBalanceNotEnoughException())->setStatusCode(400);
        }

        try {
            DB::beginTransaction();
            // decrease source balance + fee
            $this->transferMoneyRepo->decreaseUserBalance($transferDetail->getUserSourceId(), $needBalance);
            // increase destination balance
            $this->transferMoneyRepo->increaseUserBalance($transferDetail->getUserDestId(), $transferDetail->getAmount());
            // add transactions
            $this->addTransactions($transferDetail);

            DB::commit();

            return resolve(TransferDetailResponseDTO::class)
                ->setFee($fee)
                ->setStatus(true);
        } catch (Throwable $exception) {
            report($exception);
            DB::rollBack();

            return resolve(TransferDetailResponseDTO::class)
                ->setStatus(false);
        }

    }

    public function addTransactions(TransferDetailRequestDTO $transferDetail): void
    {
        //Deposit Transaction For Source User
        $this->transactionRepo->add(
            resolve(TransactionCreateDTO::class)
                ->setAmount($transferDetail->getAmount())
                ->setUserId($transferDetail->getUserSourceId())
                ->setType(TransactionTypeEnum::DEPOSIT)
        );
        //Withdraw Transaction For Destination User
        $this->transactionRepo->add(
            resolve(TransactionCreateDTO::class)
                ->setAmount($transferDetail->getAmount() * -1)
                ->setUserId($transferDetail->getUserDestId())
                ->setType(TransactionTypeEnum::WITHDRAW)
        );

        //Fee Transaction For Source User
        $this->transactionRepo->add(
            resolve(TransactionCreateDTO::class)
                ->setAmount(config('transaction.fee') * -1)
                ->setUserId($transferDetail->getUserDestId())
                ->setType(TransactionTypeEnum::WITHDRAW)
        );
    }
}
