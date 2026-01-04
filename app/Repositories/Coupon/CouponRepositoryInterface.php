<?php

namespace App\Repositories\Coupon;

use App\Repositories\Base\BaseRepositoryInterface;

interface CouponRepositoryInterface extends BaseRepositoryInterface
{
    public function dataTable();
    public function findByCode($code);
    public function findActiveUserCode($code , $userId);
}
