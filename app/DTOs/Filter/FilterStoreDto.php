<?php

namespace App\DTOs\Filter;

class FilterStoreDto
{
    public function __construct(
        public string $name,
        public int $category_id,
        public int $status,
        public mixed $type = null,
        public array $items = [],
    )
    {
    }
}
