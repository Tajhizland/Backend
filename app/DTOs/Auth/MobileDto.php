<?php

namespace App\DTOs\Auth;

class MobileDto
{
    public function __construct(
        public string $mobile,
    )
    {
    }
}
