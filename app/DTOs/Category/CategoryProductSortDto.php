<?php

namespace App\DTOs\Category;

class CategoryProductSortDto
{
    public function __construct(
        public array $product,
    )
    {
    }
}
