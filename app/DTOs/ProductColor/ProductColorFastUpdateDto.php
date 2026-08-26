<?php

namespace App\DTOs\ProductColor;

class ProductColorFastUpdateDto
{
    public function __construct(
        public array $color = [],
    )
    {
    }
}
