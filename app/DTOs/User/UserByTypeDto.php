<?php

namespace App\DTOs\User;

class UserByTypeDto
{
    public function __construct(
        public string $type,
    )
    {
    }
}
