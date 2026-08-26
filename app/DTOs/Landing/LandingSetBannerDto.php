<?php

namespace App\DTOs\Landing;

class LandingSetBannerDto
{
    public function __construct(
        public int    $landing_id,
        public mixed  $image,
        public string $url,
        public int    $slider,
    )
    {
    }
}
