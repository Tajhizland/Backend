<?php

namespace App\DTOs\Option;

class OptionStoreDto
{
    public function __construct(
        public string $title,
        public int    $category_id,
        public int    $status,
        public array  $items = [],
    )
    {
    }
}
