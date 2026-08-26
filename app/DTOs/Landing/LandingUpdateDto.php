<?php

namespace App\DTOs\Landing;

class LandingUpdateDto
{
    public function __construct(
        public int     $landingId,
        public string  $title,
        public string  $url,
        public int     $status,
        public ?string $description = null,
    )
    {
    }
}
