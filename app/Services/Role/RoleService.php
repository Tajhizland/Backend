<?php

namespace App\Services\Role;

use App\DTOs\Role\RoleStoreDto;
use App\DTOs\Role\RoleUpdateDto;
use App\Repositories\Role\RoleRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repositories\RolePermission\RolePermissionRepositoryInterface;

readonly class RoleService implements RoleServiceInterface
{
    public function __construct
    (
        private RoleRepositoryInterface           $roleRepository,
        private RolePermissionRepositoryInterface $rolePermissionRepository,
    )
    {
    }

    public function dataTable(): mixed
    {
        return $this->roleRepository->dataTable();
    }

    public function getAll(): mixed
    {
        return $this->roleRepository->all();
    }

    public function find(int $id): mixed
    {
        $role = $this->roleRepository->findWithPermission($id);
        if (!$role) {
            throw new NotFoundHttpException();
        }
        return $role;
    }

    public function store(RoleStoreDto $dto): mixed
    {
        $role = $this->roleRepository->create(["name" => $dto->name]);
        foreach ($dto->permissions as $item) {
            $this->rolePermissionRepository->create([
                "role_id" => $role->id,
                "permission_id" => $item
            ]);
        }
        return $role;
    }

    public function update(RoleUpdateDto $dto): bool
    {
        $role = $this->roleRepository->findOrFail($dto->roleId);
        $this->rolePermissionRepository->deleteByRole($dto->roleId);
        foreach ($dto->permissions as $item) {
            $this->rolePermissionRepository->create([
                "role_id" => $dto->roleId,
                "permission_id" => $item
            ]);
        }
        return $this->roleRepository->update($role, ["name" => $dto->name]);
    }
}
