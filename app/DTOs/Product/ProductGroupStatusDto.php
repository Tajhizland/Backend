<?php

namespace App\DTOs\Product;

class ProductGroupStatusDto
{
    public function __construct(
        public mixed $status,
        public array $ids = [],
    )
    {
    }
}
