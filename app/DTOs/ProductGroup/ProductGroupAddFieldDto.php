<?php

namespace App\DTOs\ProductGroup;

class ProductGroupAddFieldDto
{
    public function __construct(
        public string $title,
        public int    $groupId,
    )
    {
    }
}
