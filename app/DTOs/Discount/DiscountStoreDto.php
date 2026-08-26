<?php

namespace App\DTOs\Discount;

class DiscountStoreDto
{
    public function __construct(
        public string $title,
        public int    $status,
        public mixed  $start_date = null,
        public mixed  $end_date = null,
    )
    {
    }
}
