<?php

namespace App\DTOs\Slider;

class SliderStoreDto
{
    public function __construct(
        public string $title,
        public string $url,
        public string $type,
        public mixed  $image,
        public int    $status,
    )
    {
    }
}
