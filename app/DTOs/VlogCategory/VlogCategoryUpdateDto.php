<?php

namespace App\DTOs\VlogCategory;

class VlogCategoryUpdateDto
{
    public function __construct(
        public int    $vlogCategoryId,
        public string $name,
        public string $url,
        public int    $status,
        public mixed  $icon = null,
    )
    {
    }
}
