<?php

namespace App\DTOs\Slider;

class SliderUpdateDto
{
    public function __construct(
        public int    $sliderId,
        public string $title,
        public string $url,
        public string $type,
        public int    $status,
        public mixed  $image = null,
    )
    {
    }
}
