<?php

namespace App\Services\Coupon;

use App\Repositories\Coupon\CouponRepositoryInterface;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CouponService implements CouponServiceInterface
{
    public function __construct
    (
        private CouponRepositoryInterface $couponRepository
    )
    {
    }

    public function dataTable()
    {
        return $this->couponRepository->dataTable();
    }

    public function generate()
    {
        $allow = false;
        while ($allow == false) {
            $code = Str::random(6);
            $exist = $this->couponRepository->findByCode($code);
            if (!$exist)
                $allow = true;
        }
        return $code;
    }

    public function find($id)
    {
        return $this->couponRepository->findOrFail($id);
    }

    public function store($code, $status, $price, $percent, $user_id, $start_time, $end_time, $min_order_value, $max_order_value)
    {
        return $this->couponRepository->create([
            "code" => $code,
            "status" => $status,
            "price" => $price,
            "percent" => $percent,
            "user_id" => $user_id,
            "start_time" => $start_time,
            "end_time" => $end_time,
            "min_order_value" => $min_order_value,
            "max_order_value" => $max_order_value,
        ]);
    }

    public function update($id, $code, $status, $price, $percent, $user_id, $start_time, $end_time, $min_order_value, $max_order_value)
    {
        $coupon = $this->couponRepository->findOrFail($id);
        return $this->couponRepository->update($coupon, [
            "code" => $code,
            "status" => $status,
            "price" => $price,
            "percent" => $percent,
            "user_id" => $user_id,
            "start_time" => $start_time,
            "end_time" => $end_time,
            "min_order_value" => $min_order_value,
            "max_order_value" => $max_order_value,
        ]);
    }

    public function check($code, $userId)
    {
        $coupon = $this->couponRepository->findActiveUserCode($code, $userId);
        if (!$coupon) {
            throw new  BadRequestHttpException("کد تخفیف یافت نشد");
        }
        return $coupon;


    }
}
