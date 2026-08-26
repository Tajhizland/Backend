<?php

namespace App\DTOs\Product;

class ProductGroupPriceDto
{
    public function __construct(
        public mixed $percent,
        public string $action,
        public array $ids = [],
    )
    {
    }
}
