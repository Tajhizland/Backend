<?php

namespace App\DTOs\CastCategory;

class CastCategoryStoreDto
{
    public function __construct(
        public string $name,
        public int    $status,
        public mixed  $icon,
    )
    {
    }
}
