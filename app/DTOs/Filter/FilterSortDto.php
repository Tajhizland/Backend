<?php

namespace App\DTOs\Filter;

class FilterSortDto
{
    public function __construct(
        public array $filter,
    )
    {
    }
}
