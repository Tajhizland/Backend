<?php

namespace App\DTOs\SpecialProduct;

class SpecialProductHomepageDto
{
    public function __construct(
        public int $specialProductId,
        public int $homepage,
    )
    {
    }
}
