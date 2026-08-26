<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Coupon\CouponStoreDto;
use App\DTOs\Coupon\CouponStoreGroupDto;
use App\DTOs\Coupon\CouponUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Coupon\StoreCouponRequest;
use App\Http\Requests\Admin\Coupon\StoreGroupCouponRequest;
use App\Http\Requests\Admin\Coupon\UpdateCouponRequest;
use App\Http\Resources\Coupon\CouponResource;
use App\Services\Coupon\CouponServiceInterface;

class CouponController extends Controller
{
    public function __construct(
        private readonly CouponServiceInterface $couponService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(CouponResource::collection($this->couponService->dataTable()));
    }

    public function generate()
    {
        return $this->dataResponse(["code" => $this->couponService->generate()]);
    }

    public function show($id)
    {
        return $this->dataResponse(CouponResource::make($this->couponService->find($id)));
    }

    public function store(StoreCouponRequest $request)
    {
        $this->couponService->store(new CouponStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.discount")]));
    }

    public function storeGroup(StoreGroupCouponRequest $request)
    {
        $this->couponService->storeGroup(new CouponStoreGroupDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.discount")]));
    }

    public function update($id, UpdateCouponRequest $request)
    {
        $this->couponService->update(new CouponUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.discount")]));
    }
}
