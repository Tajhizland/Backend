<?php

namespace App\DTOs\BlogCategory;

class BlogCategoryStoreDto
{
    public function __construct(
        public string $name,
        public string $url,
        public int    $status,
    )
    {
    }
}
