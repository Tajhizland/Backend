<?php

namespace App\DTOs\OnHoldOrder;

class OnHoldOrderCouponDto
{
    public function __construct(
        public int $onHoldOrderId,
        public int $userId,
        public string $code,
    )
    {
    }
}
