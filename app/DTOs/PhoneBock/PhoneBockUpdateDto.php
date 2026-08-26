<?php

namespace App\DTOs\PhoneBock;

class PhoneBockUpdateDto
{
    public function __construct(
        public int     $phoneBockId,
        public string  $mobile,
        public ?string $name = null,
    )
    {
    }
}
