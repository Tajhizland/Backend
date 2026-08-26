<?php

namespace App\DTOs\Compare;

class CompareSearchDto
{
    public function __construct(
        public string $query,
        public mixed $categoryIds = null,
    )
    {
    }
}
