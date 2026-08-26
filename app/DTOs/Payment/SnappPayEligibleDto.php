<?php

namespace App\DTOs\Payment;

class SnappPayEligibleDto
{
    public function __construct(
        public mixed $amount,
    )
    {
    }
}
