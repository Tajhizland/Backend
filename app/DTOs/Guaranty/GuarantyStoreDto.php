<?php

namespace App\DTOs\Guaranty;

class GuarantyStoreDto
{
    public function __construct(
        public string  $name,
        public string  $url,
        public int     $free,
        public int     $status,
        public ?string $description = null,
        public mixed   $icon = null,
    )
    {
    }
}
