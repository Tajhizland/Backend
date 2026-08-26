<?php

namespace App\DTOs\Option;

class OptionItemUpdateDto
{
    public function __construct(
        public int    $categoryId,
        public string $title,
        public int    $status,
        public mixed  $id = null,
    )
    {
    }
}
