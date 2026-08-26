<?php

namespace App\Services\Permission;

use App\DTOs\Permission\PermissionStoreDto;
use App\DTOs\Permission\PermissionUpdateDto;

interface PermissionServiceInterface
{
    public function dataTable(): mixed;

    public function getAll(): mixed;

    public function find(int $id): mixed;

    public function store(PermissionStoreDto $dto): mixed;

    public function update(PermissionUpdateDto $dto): bool;
}
