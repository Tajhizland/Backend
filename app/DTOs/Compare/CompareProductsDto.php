<?php

namespace App\DTOs\Compare;

class CompareProductsDto
{
    public function __construct(
        public mixed $categoryIds = null,
    )
    {
    }
}
