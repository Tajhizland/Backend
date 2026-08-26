<?php

namespace App\DTOs\Filter;

class FilterUpdateDto
{
    public function __construct(
        public int $filterId,
        public string $name,
        public int $category_id,
        public mixed $type = null,
        public mixed $status = null,
        public array $items = [],
    )
    {
    }
}
