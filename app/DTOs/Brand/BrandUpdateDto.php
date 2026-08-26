<?php

namespace App\DTOs\Brand;

class BrandUpdateDto
{
    public function __construct(
        public int     $brandId,
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
