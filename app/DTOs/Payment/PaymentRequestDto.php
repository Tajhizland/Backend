<?php

namespace App\DTOs\Payment;

class PaymentRequestDto
{
    public function __construct(
        public int $userId,
        public mixed $wallet = null,
        public mixed $shippingMethod = 1,
        public mixed $code = null,
        public mixed $shippingPrice = 0,
        public mixed $gateway = 1,
    )
    {
    }
}
