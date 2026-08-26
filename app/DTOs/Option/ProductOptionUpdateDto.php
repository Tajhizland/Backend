<?php

namespace App\DTOs\Option;

class ProductOptionUpdateDto
{
    public function __construct(
        public array $options = [],
    )
    {
    }
}
