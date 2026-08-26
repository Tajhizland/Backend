<?php

namespace App\DTOs\Guaranty;

class GuarantyUpdateDto
{
    public function __construct(
        public int     $guarantyId,
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
