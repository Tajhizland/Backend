<?php

namespace App\DTOs\VlogCategory;

class VlogCategoryStoreDto
{
    public function __construct(
        public string $name,
        public string $url,
        public int    $status,
        public mixed  $icon = null,
    )
    {
    }
}
