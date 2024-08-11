<?php

namespace App\Repositories\Transaction;

use App\Repositories\Base\BaseRepositoryInterface;

interface TransactionRepositoryInterface extends  BaseRepositoryInterface
{
    public function createTransaction($userId,$orderId,$trackId,$price);
}
