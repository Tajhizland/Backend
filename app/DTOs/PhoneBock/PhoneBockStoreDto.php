<?php

namespace App\DTOs\PhoneBock;

class PhoneBockStoreDto
{
    public function __construct(
        public string  $mobile,
        public ?string $name = null,
    )
    {
    }
}
