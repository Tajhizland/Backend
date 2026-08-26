<?php

namespace App\Services\Role;

use App\DTOs\Role\RoleStoreDto;
use App\DTOs\Role\RoleUpdateDto;

interface RoleServiceInterface
{
    public function dataTable(): mixed;

    public function getAll(): mixed;

    public function find(int $id): mixed;

    public function store(RoleStoreDto $dto): mixed;

    public function update(RoleUpdateDto $dto): bool;
}
