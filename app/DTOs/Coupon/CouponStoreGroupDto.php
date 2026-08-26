<?php

namespace App\DTOs\Coupon;

class CouponStoreGroupDto
{
    public function __construct(
        public mixed $userIds,
        public int   $status,
        public mixed $price = null,
        public mixed $percent = null,
        public mixed $start_time = null,
        public mixed $end_time = null,
        public mixed $min_order_value = null,
        public mixed $max_order_value = null,
        public mixed $send_sms = false,
        public mixed $message = null,
    )
    {
    }
}
