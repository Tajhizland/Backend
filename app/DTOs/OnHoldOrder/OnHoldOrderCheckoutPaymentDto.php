<?php

namespace App\DTOs\OnHoldOrder;

class OnHoldOrderCheckoutPaymentDto
{
    public function __construct(
        public int $onHoldOrderId,
        public int $userId,
        public mixed $wallet = null,
        public mixed $shippingMethod = null,
        public mixed $code = null,
        public mixed $gateway = 1,
    )
    {
    }
}
