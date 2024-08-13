<?php

namespace App\Repositories\Transaction;

use App\Models\Transaction;
use App\Repositories\Base\BaseRepository;
use App\Services\Sort\Transaction\SortTransactionByUserMobile;
use App\Services\Sort\Transaction\SortTransactionByUserName;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

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

    public function dataTable()
    {
        return QueryBuilder::for(Transaction::class)
            ->allowedFilters(['user_id', 'order_id', 'track_id', 'price',
                AllowedFilter::callback('user', function ($query, $value) {
                    $query->whereHas('user', function ($query) use ($value) {
                        $query->where('name', 'like', '%' . $value . '%');
                    });
                }),
                AllowedFilter::callback('mobile', function ($query, $value) {
                    $query->whereHas('user', function ($query) use ($value) {
                        $query->where('username', 'like', '%' . $value . '%');
                    });
                })
            ])
            ->allowedSorts(['user_id', 'order_id', 'track_id', 'price'
                , AllowedSort::custom("user", new SortTransactionByUserName())
                , AllowedSort::custom("mobile", new SortTransactionByUserMobile())
            ])
            ->paginate($this->pageSize);
    }
}

