<?php

namespace App\DTOs\Role;

class RoleStoreDto
{
    public function __construct(
        public string $name,
        public array  $permissions = [],
    )
    {
    }
}
