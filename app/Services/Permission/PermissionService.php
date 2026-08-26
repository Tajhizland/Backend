<?php

namespace App\Services\Permission;

use App\DTOs\Permission\PermissionStoreDto;
use App\DTOs\Permission\PermissionUpdateDto;
use App\Repositories\Permission\PermissionRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class PermissionService implements PermissionServiceInterface
{
    public function __construct
    (
        private PermissionRepositoryInterface $permissionRepository
    )
    {
    }

    public function dataTable(): mixed
    {
        return $this->permissionRepository->dataTable();
    }

    public function getAll(): mixed
    {
        return $this->permissionRepository->all();
    }

    public function find(int $id): mixed
    {
        $permission = $this->permissionRepository->find($id);
        if (!$permission) {
            throw new NotFoundHttpException();
        }
        return $permission;
    }

    public function store(PermissionStoreDto $dto): mixed
    {
        return $this->permissionRepository->create(["name" => $dto->name, "value" => $dto->value]);
    }

    public function update(PermissionUpdateDto $dto): bool
    {
        $permission = $this->find($dto->permissionId);
        return $this->permissionRepository->update($permission, ["name" => $dto->name, "value" => $dto->value]);
    }
}
