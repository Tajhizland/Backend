<?php

namespace App\DTOs\CastCategory;

class CastCategoryUpdateDto
{
    public function __construct(
        public int    $castCategoryId,
        public string $name,
        public int    $status,
        public mixed  $icon = null,
    )
    {
    }
}
