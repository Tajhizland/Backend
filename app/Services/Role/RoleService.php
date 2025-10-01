<?php

namespace App\Services\Role;

use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\RolePermission\RolePermissionRepositoryInterface;

class RoleService implements RoleServiceInterface
{
    public function __construct
    (
        private RoleRepositoryInterface           $roleRepository,
        private RolePermissionRepositoryInterface $rolePermissionRepository,
    )
    {
    }

    public function dataTable()
    {
        return $this->roleRepository->dataTable();
    }

    public function getAll()
    {
        return $this->roleRepository->all();
    }

    public function find($id)
    {
        return $this->roleRepository->findWithPermission($id);
    }

    public function store($name, $permission)
    {
        $role = $this->roleRepository->create(["name" => $name]);
        foreach ($permission as $item) {
            $this->rolePermissionRepository->create([
                "role_id" => $role->id,
                "permission_id" => $item
            ]);
        }
        return $role;
    }

    public function update($id, $name, $permission)
    {
        $model = $this->roleRepository->findOrFail($id);
        $this->rolePermissionRepository->deleteByRole($id);
        foreach ($permission as $item) {
            $this->rolePermissionRepository->create([
                "role_id" => $id,
                "permission_id" => $item
            ]);
        }
        return $this->roleRepository->update($model, ["name" => $name]);
    }
}
