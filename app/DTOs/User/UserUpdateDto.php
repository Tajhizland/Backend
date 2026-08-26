<?php

namespace App\DTOs\User;

class UserUpdateDto
{
    public function __construct(
        public int     $userId,
        public string  $name,
        public string  $last_name,
        public string  $national_code,
        public string  $username,
        public string  $role,
        public ?string $email = null,
        public mixed   $gender = null,
        public mixed   $role_id = null,
    )
    {
    }
}
