<?php

namespace App\DTOs\Option;

class OptionSetDto
{
    public function __construct(
        public int   $category_id,
        public array $option = [],
    )
    {
    }
}
