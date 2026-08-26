<?php

namespace App\DTOs\Product;

class ProductSearchListDto
{
    public function __construct(
        public mixed $categoryId = null,
        public mixed $brandId = null,
        public mixed $searchQuery = null,
        public mixed $discountId = null,
    )
    {
    }
}
