<?php

namespace App\Services\Permission;

use App\Repositories\Permission\PermissionRepositoryInterface;

class PermissionService implements PermissionServiceInterface
{
    public function __construct
    (
        private PermissionRepositoryInterface $permissionRepository
    )
    {
    }

    public function dataTable()
    {
        return $this->permissionRepository->dataTable();
    }

    public function getAll()
    {
        return $this->permissionRepository->all();
    }

    public function find($id)
    {
        return $this->permissionRepository->findOrFail($id);
    }

    public function store($name, $value)
    {
        return $this->permissionRepository->create(["name" => $name, "value" => $value]);
    }

    public function update($id, $name, $value)
    {
        $model = $this->permissionRepository->findOrFail($id);
        return $this->permissionRepository->update($model, ["name" => $name, "value" => $value]);
    }
}
