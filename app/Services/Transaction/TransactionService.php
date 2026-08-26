<?php

namespace App\Services\Transaction;

use App\Repositories\Transaction\TransactionRepositoryInterface;

readonly class TransactionService implements TransactionServiceInterface
{

    public function __construct
    (
      private  TransactionRepositoryInterface $transactionRepository
    )
    {
    }

    public function dataTable(): mixed
    {
        return $this->transactionRepository->dataTable();
    }
}
