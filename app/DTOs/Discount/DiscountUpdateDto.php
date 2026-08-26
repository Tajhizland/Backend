<?php

namespace App\DTOs\Discount;

class DiscountUpdateDto
{
    public function __construct(
        public int    $discountId,
        public string $title,
        public int    $status,
        public mixed  $start_date = null,
        public mixed  $end_date = null,
    )
    {
    }
}
