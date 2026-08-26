<?php

namespace App\DTOs\Banner;

class BannerStoreDto
{
    public function __construct(
        public mixed  $image,
        public string $url,
        public mixed  $type,
    )
    {
    }
}
