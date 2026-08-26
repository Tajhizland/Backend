<?php

namespace App\DTOs\Product;

class ProductGroupStockDto
{
    public function __construct(
        public mixed $stock,
        public array $ids = [],
    )
    {
    }
}
