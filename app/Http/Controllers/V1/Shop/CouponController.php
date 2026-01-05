<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Coupon\CheckCouponRequest;
use App\Http\Resources\V1\Coupon\CouponResource;
use App\Services\Coupon\CouponServiceInterface;
use Illuminate\Support\Facades\Auth;

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
        $response = $this->couponService->check($request->get("code"), Auth::id());
        return $this->dataResponse(new CouponResource($response));
    }
}
