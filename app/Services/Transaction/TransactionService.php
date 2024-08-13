<?php

namespace App\Services\Transaction;

use App\Repositories\Transaction\TransactionRepositoryInterface;

class TransactionService implements TransactionServiceInterface
{

    public function __construct
    (
      private  TransactionRepositoryInterface $transactionRepository
    )
    {
    }

    public function dataTable()
    {
        return $this->transactionRepository->dataTable();
    }
}
