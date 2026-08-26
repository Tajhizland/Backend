<?php

namespace App\DTOs\VlogCategory;

class VlogCategorySortDto
{
    public function __construct(
        public array $vlogs,
    )
    {
    }
}
