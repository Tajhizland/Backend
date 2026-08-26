<?php

namespace App\DTOs\Category;

class CategoryStoreDto
{
    public function __construct(
        public string  $name,
        public string  $url,
        public string  $type,
        public int     $parent_id,
        public int     $status,
        public mixed   $image = null,
        public ?string $description = null,
    )
    {
    }
}
