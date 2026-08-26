<?php

namespace App\DTOs\Permission;

class PermissionUpdateDto
{
    public function __construct(
        public int    $permissionId,
        public string $name,
        public string $value,
    )
    {
    }
}
