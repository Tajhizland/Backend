<?php

namespace App\DTOs\Filter;

class FilterSetDto
{
    public function __construct(
        public int   $category_id,
        public array $filter = [],
    )
    {
    }
}
