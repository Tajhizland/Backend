<?php

namespace App\DTOs\RandomProductCategory;

class RandomProductCategoryAddDto
{
    public function __construct(
        public int $category_id,
    )
    {
    }
}
