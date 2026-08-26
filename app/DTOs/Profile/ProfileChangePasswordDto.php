<?php

namespace App\DTOs\Profile;

class ProfileChangePasswordDto
{
    public function __construct(
        public string $current_password,
        public string $new_password,
    )
    {
    }
}
