<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Coupon\CheckCouponRequest;
use App\Services\Coupon\CouponServiceInterface;

class CouponController extends Controller
{
    public function __construct
    (
        private CouponServiceInterface $couponService
    )
    {
    }

    public function check(CheckCouponRequest $request)
    {

    }
}
