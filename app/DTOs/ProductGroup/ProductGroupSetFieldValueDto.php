<?php

namespace App\DTOs\ProductGroup;

class ProductGroupSetFieldValueDto
{
    public function __construct(
        public int   $groupProductId,
        public int   $fieldId,
        public mixed $value = null,
    )
    {
    }
}
