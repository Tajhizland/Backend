<?php

namespace App\DTOs\HomepageCategory;

class HomepageCategoryAddDto
{
    public function __construct(
        public int $category_id,
    )
    {
    }
}
