<?php

namespace App\DTOs\PopularCategory;

class PopularCategoryAddDto
{
    public function __construct(
        public int $category_id,
    )
    {
    }
}
