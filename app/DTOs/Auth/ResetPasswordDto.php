<?php

namespace App\DTOs\Auth;

class ResetPasswordDto
{
    public function __construct(
        public string $mobile,
        public string $password,
    )
    {
    }
}
