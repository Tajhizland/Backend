<?php

namespace App\DTOs\Permission;

class PermissionStoreDto
{
    public function __construct(
        public string $name,
        public string $value,
    )
    {
    }
}
