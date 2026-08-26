<?php

namespace App\DTOs\Auth;

class LoginDto
{
    public function __construct(
        public string $username,
        public string $password,
    )
    {
    }
}
