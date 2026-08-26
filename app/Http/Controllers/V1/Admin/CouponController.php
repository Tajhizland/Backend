<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Coupon\StoreCouponRequest;
use App\Http\Requests\Admin\Coupon\StoreGroupCouponRequest;
use App\Http\Requests\Admin\Coupon\UpdateCouponRequest;
use App\Http\Resources\Coupon\CouponResource;
use App\Services\Coupon\CouponServiceInterface;

class CouponController extends Controller
{
    public function __construct
    (
        private readonly CouponServiceInterface $couponService
    )
    {
    }

    public function dataTable()
    {
        $response = $this->couponService->dataTable();
        return $this->dataResponseCollection(CouponResource::collection($response));
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
        return $this->successResponse(__("action.store", ["attr" => __("attr.discount")]));
    }
    public function storeGroup(StoreGroupCouponRequest $request)
    {
        $this->couponService->storeGroup(
            $request->get("status"),
            $request->get("price"),
            $request->get("percent"),
            $request->get("userIds"),
            $request->get("start_time"),
            $request->get("end_time"),
            $request->get("min_order_value"),
            $request->get("max_order_value"),
            $request->boolean("send_sms"),
            $request->get("message"),
        );
        return $this->successResponse(__("action.store", ["attr" => __("attr.discount")]));
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
        return $this->successResponse(__("action.update", ["attr" => __("attr.discount")]));
    }
}
