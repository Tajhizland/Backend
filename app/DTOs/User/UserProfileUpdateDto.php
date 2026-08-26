<?php

namespace App\DTOs\User;

class UserProfileUpdateDto
{
    public function __construct(
        public int $userId,
        public string $name,
        public string $last_name,
        public string $national_code,
        public mixed $email = null,
        public mixed $gender = null,
        public mixed $avatar = null,
    )
    {
    }
}
