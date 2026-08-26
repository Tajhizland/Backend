<?php

namespace App\Services\Payment;

use App\Services\Coupon\CouponServiceInterface;
use App\Services\Payment\Data\AppliedCoupon;

/**
 * محاسبه‌ی مبلغ تخفیف کوپن. قبلاً همین بلوک if/elseif دو بار در PaymentService تکرار شده بود.
 */
readonly class CouponCalculator
{
    public function __construct(private CouponServiceInterface $couponService)
    {
    }

    /**
     * @param  int|float  $amount       مبلغ پایه برای محاسبه‌ی درصد تخفیف
     * @param  int|float  $extraAmount  مبلغ پایه‌ی دیجی‌پی برای محاسبه‌ی درصد تخفیف
     * @param  int|float|null  $totalItemsPrice  برای بررسی حداقل مبلغ خرید کوپن (null یعنی بررسی نشود)
     */
    public function apply($code, $userId, int|float $amount, int|float $extraAmount, $totalItemsPrice = null): AppliedCoupon
    {
        if ($code === null) {
            return new AppliedCoupon();
        }

        $coupon = $this->couponService->check($code, $userId, $totalItemsPrice);
        if (!$coupon) {
            return new AppliedCoupon();
        }

        if ($coupon->price) {
            return new AppliedCoupon($coupon, $coupon->price);
        }

        if ($coupon->percent) {
            return new AppliedCoupon(
                $coupon,
                $amount * $coupon->percent / 100,
                $extraAmount * $coupon->percent / 100,
            );
        }

        return new AppliedCoupon($coupon);
    }
}
