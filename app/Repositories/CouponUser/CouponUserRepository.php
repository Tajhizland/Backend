<?php

namespace App\Repositories\CouponUser;

use App\Models\CouponUser;
use App\Repositories\Base\BaseRepository;

class CouponUserRepository extends BaseRepository implements CouponUserRepositoryInterface
{
    public function __construct(CouponUser $model)
    {
        parent::__construct($model);
    }

    public function deleteByOrderId($orderId)
    {
        return $this->model::where("order_id", $orderId)->delete();
    }
}
