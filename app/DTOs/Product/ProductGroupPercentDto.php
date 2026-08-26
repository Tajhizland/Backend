<?php

namespace App\DTOs\Product;

class ProductGroupPercentDto
{
    public function __construct(
        public mixed $percent,
        public array $ids = [],
    )
    {
    }
}
