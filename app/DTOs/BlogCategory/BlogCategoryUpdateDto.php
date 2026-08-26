<?php

namespace App\DTOs\BlogCategory;

class BlogCategoryUpdateDto
{
    public function __construct(
        public int    $blogCategoryId,
        public string $name,
        public string $url,
        public int    $status,
    )
    {
    }
}
