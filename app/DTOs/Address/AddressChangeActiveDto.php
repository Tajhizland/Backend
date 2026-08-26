<?php

namespace App\DTOs\Address;

class AddressChangeActiveDto
{
    public function __construct(
        public int   $user_id,
        public mixed $id = null,
    )
    {
    }
}
