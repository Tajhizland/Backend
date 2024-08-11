<?php

namespace App\Repositories\Transaction;

use App\Models\Transaction;
use App\Repositories\Base\BaseRepository;

class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    public function __construct(Transaction $model)
    {
        parent::__construct($model);
    }

    public function createTransaction($userId, $orderId, $trackId, $price)
    {
        return $this->create([
            "user_id" => $userId,
            "order_id" => $orderId,
            "track_id" => $trackId,
            "price" => $price,
        ]);
    }
}

