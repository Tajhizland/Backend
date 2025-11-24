<?php

namespace App\Repositories\Discount;

use App\Models\Discount;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class DiscountRepository extends BaseRepository implements DiscountRepositoryInterface
{
    public function __construct(Discount $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(Discount::class)
            ->allowedFilters(['title', 'id', 'status', 'start_date', 'end_date', 'created_at', 'updated_at'])
            ->allowedSorts(['title', 'id', 'status', 'start_date', 'end_date', 'created_at', 'updated_at'])
            ->paginate($this->pageSize);
    }
}
