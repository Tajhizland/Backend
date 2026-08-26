<?php

namespace App\DTOs\Category;

class CategoryUpdateDto
{
    public function __construct(
        public int     $categoryId,
        public string  $name,
        public string  $url,
        public string  $type,
        public int     $parent_id,
        public mixed   $status = null,
        public mixed   $image = null,
        public ?string $description = null,
    )
    {
    }
}
