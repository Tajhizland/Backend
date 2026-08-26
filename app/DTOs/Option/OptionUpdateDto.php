<?php

namespace App\DTOs\Option;

class OptionUpdateDto
{
    public function __construct(
        public int    $optionId,
        public string $title,
        public int    $category_id,
        public int    $status,
        public array  $items = [],
    )
    {
    }
}
