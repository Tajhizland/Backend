<?php

namespace App\DTOs\Category;

class CategoryListingDto
{
    public function __construct(
        public string $url,
        public mixed $filter = null,
    )
    {
    }
}
