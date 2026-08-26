<?php

namespace App\DTOs\Coupon;

class CouponUpdateDto
{
    public function __construct(
        public int    $couponId,
        public string $code,
        public int    $status,
        public mixed  $price = null,
        public mixed  $percent = null,
        public mixed  $user_id = null,
        public mixed  $start_time = null,
        public mixed  $end_time = null,
        public mixed  $min_order_value = null,
        public mixed  $max_order_value = null,
    )
    {
    }
}
