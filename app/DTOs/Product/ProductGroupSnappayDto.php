<?php

namespace App\DTOs\Product;

class ProductGroupSnappayDto
{
    public function __construct(
        public mixed $snappay,
        public array $ids = [],
    )
    {
    }
}
