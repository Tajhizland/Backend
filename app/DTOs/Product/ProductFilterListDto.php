<?php

namespace App\DTOs\Product;

class ProductFilterListDto
{
    public function __construct(
        public mixed $filter = null,
    )
    {
    }
}
