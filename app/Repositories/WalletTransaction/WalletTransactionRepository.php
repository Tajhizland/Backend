<?php

namespace App\Repositories\WalletTransaction;

use App\Models\WalletTransaction;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class WalletTransactionRepository extends BaseRepository implements WalletTransactionRepositoryInterface
{
    public function __construct(WalletTransaction $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(WalletTransaction::class)
            ->allowedFilters(['user_id', 'amount', 'status', 'id', 'created_at'])
            ->allowedSorts(['user_id', 'amount', 'status', 'id', 'created_at'])
            ->paginate($this->pageSize);
    }
}
