<?php

namespace App\DTOs\Gateway;

class GatewayUpdateDto
{
    public function __construct(
        public int     $gatewayId,
        public string  $name,
        public int     $status,
        public ?string $description = null,
    )
    {
    }
}
