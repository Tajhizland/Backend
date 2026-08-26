<?php

namespace App\DTOs\Auth;

class RegisterDto
{
    public function __construct(
        public string $mobile,
        public string $password,
        public string $name,
        public string $last_name,
        public mixed $national_code = null,
    )
    {
    }
}
