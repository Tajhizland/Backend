<?php

namespace App\DTOs\Order;

class DigipayCalcDto
{
    public function __construct(
        public string $start_date,
        public string $end_date,
    )
    {
    }
}
