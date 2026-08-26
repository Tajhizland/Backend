<?php

namespace App\DTOs\Brand;

class BrandStoreDto
{
    public function __construct(
        public string  $name,
        public string  $url,
        public int     $status,
        public mixed   $image = null,
        public mixed   $banner = null,
        public ?string $description = null,
    )
    {
    }
}
