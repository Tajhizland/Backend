<?php

namespace App\DTOs\Coupon;

class CouponCheckDto
{
    public function __construct(
        public int $userId,
        public string $code,
    )
    {
    }
}
