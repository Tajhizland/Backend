<?php

namespace App\DTOs\Gateway;

class GatewayStoreDto
{
    public function __construct(
        public string  $name,
        public int     $status,
        public ?string $description = null,
    )
    {
    }
}
