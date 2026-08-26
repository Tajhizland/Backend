<?php

namespace App\DTOs\Search;

class SearchQueryDto
{
    public function __construct(
        public string $query,
    )
    {
    }
}
