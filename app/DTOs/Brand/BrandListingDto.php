<?php

namespace App\DTOs\Brand;

class BrandListingDto
{
    public function __construct(
        public string $url,
        public mixed $filter = null,
    )
    {
    }
}
