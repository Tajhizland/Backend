<?php

namespace App\DTOs\Role;

class RoleUpdateDto
{
    public function __construct(
        public int    $roleId,
        public string $name,
        public array  $permissions = [],
    )
    {
    }
}
