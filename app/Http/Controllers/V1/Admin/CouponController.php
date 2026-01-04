<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Coupon\StoreCouponRequest;
use App\Http\Requests\V1\Admin\Coupon\UpdateCouponRequest;
use App\Http\Resources\V1\Coupon\CouponCollection;
use App\Http\Resources\V1\Coupon\CouponResource;
use App\Services\Coupon\CouponServiceInterface;
use Illuminate\Support\Facades\Lang;

class CouponController extends Controller
{
    public function __construct
    (
        private CouponServiceInterface $couponService
    )
    {
    }

    public function dataTable()
    {
        $response = $this->couponService->dataTable();
        return $this->dataResponseCollection(CouponCollection::make($response));
    }

    public function find($id)
    {
        $response = $this->couponService->find($id);
        return $this->dataResponse(CouponResource::make($response));
    }

    public function generate()
    {
        $response = $this->couponService->generate();
        return $this->dataResponse(["code" => $response]);
    }

    public function store(StoreCouponRequest $request)
    {
        $this->couponService->store(
            $request->get("code"),
            $request->get("status"),
            $request->get("price"),
            $request->get("percent"),
            $request->get("user_id"),
            $request->get("start_time"),
            $request->get("end_time"),
            $request->get("min_order_value"),
            $request->get("max_order_value"),
        );
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.discount")]));
    }

    public function update(UpdateCouponRequest $request)
    {
        $this->couponService->update(
            $request->get("id"),
            $request->get("code"),
            $request->get("status"),
            $request->get("price"),
            $request->get("percent"),
            $request->get("user_id"),
            $request->get("start_time"),
            $request->get("end_time"),
            $request->get("min_order_value"),
            $request->get("max_order_value"),
        );
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.discount")]));
    }
}
