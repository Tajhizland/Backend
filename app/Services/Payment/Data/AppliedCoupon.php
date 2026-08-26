<?php

namespace App\Services\Payment\Data;

use App\Models\Coupon;

/**
 * نتیجه‌ی اعمال یک کد تخفیف روی مبلغ سفارش.
 */
readonly class AppliedCoupon
{
    /**
     * @param  int|float  $off       تخفیف روی مبلغ عادی
     * @param  int|float  $extraOff  تخفیف روی مبلغ دیجی‌پی (بدون تخفیف محصول + کارمزد)
     */
    public function __construct(
        public readonly ?Coupon   $coupon = null,
        public readonly int|float $off = 0,
        public readonly int|float $extraOff = 0,
    )
    {
    }
}
