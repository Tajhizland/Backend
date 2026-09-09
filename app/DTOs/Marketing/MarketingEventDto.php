<?php

namespace App\DTOs\Marketing;

class MarketingEventDto
{
    public function __construct(
        public string $type,
        public ?int   $product_id = null,
        public ?int   $category_id = null,
        public ?int   $quantity = null,
        public ?array $meta = null,
    )
    {
    }
}
