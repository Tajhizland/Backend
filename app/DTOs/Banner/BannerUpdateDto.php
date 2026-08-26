<?php

namespace App\DTOs\Banner;

class BannerUpdateDto
{
    public function __construct(
        public int    $bannerId,
        public string $url,
        public mixed  $type,
        public mixed  $image = null,
    )
    {
    }
}
