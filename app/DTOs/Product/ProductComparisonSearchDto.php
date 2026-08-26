<?php

namespace App\DTOs\Product;

class ProductComparisonSearchDto
{
    public function __construct(
        public string $query,
        public mixed $category_id = null,
    )
    {
    }
}
