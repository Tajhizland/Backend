<?php

namespace App\Services\Coupon;

use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\CartItem\CartItemRepositoryInterface;
use App\Repositories\Coupon\CouponRepositoryInterface;
use App\Services\CartItem\CartItemServiceInterface;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CouponService implements CouponServiceInterface
{
    public function __construct
    (
        private CouponRepositoryInterface   $couponRepository,
        private CartRepositoryInterface     $cartRepository,
        private CartItemRepositoryInterface $cartItemRepository,
        private CartItemServiceInterface    $cartItemService,
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
        if ($coupon->min_order_value || $coupon->max_order_value) {
            $cart = $this->cartRepository->getCartByUserId($userId);
            $cartItems = $this->cartItemRepository->getItemsByCartId($cart->id);
            $cartItemsCalculate = $this->cartItemService->calculatePrice($cartItems);
            dd("salam");
            $totalItemsPrice = $cartItemsCalculate["totalItemPrice"];

            if ($coupon->min_order_value != null && $totalItemsPrice <= $coupon->min_order_value) {
                throw new  BadRequestHttpException("برای استفاده از این کد تخفیف مجموع قیمت محصولات سبد خرید باید بالای " . $coupon->min_order_value . " تومان باشد .");
            }
            if ($coupon->max_order_value != null && $totalItemsPrice >= $coupon->max_order_value) {
                throw new  BadRequestHttpException("برای استفاده از این کد تخفیف مجموع قیمت محصولات سبد خرید باید کمتر از  " . $coupon->max_order_value . " تومان باشد .");
            }
        }
        return $coupon;


    }
}
