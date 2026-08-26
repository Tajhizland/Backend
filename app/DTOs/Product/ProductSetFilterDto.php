<?php

namespace App\DTOs\Product;

class ProductSetFilterDto
{
    public function __construct(
        public int $product_id,
        public array $filter = [],
    )
    {
    }
}
