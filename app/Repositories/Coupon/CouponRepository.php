<?php

namespace App\Repositories\Coupon;

use App\Models\Coupon;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class CouponRepository extends BaseRepository implements CouponRepositoryInterface
{
    public function __construct(Coupon $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(Coupon::class)
            ->allowedFilters(['code', 'price', 'percent', 'status', 'id', 'start_time', 'end_time', 'min_order_value', 'max_order_value', 'created_at'])
            ->allowedSorts(['code', 'price', 'percent', 'status', 'id', 'start_time', 'end_time', 'min_order_value', 'max_order_value', 'created_at'])
            ->paginate($this->pageSize);
    }

    public function findByCode($code)
    {
        return $this->model::where("code", $code)->first();
    }
}
