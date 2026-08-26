<?php

namespace App\DTOs\Auth;

class VerifyCodeDto
{
    public function __construct(
        public string $mobile,
        public string $code,
    )
    {
    }
}
