<?php

namespace App\DTOs\Landing;

class LandingStoreDto
{
    public function __construct(
        public string  $title,
        public string  $url,
        public int     $status,
        public ?string $description = null,
    )
    {
    }
}
