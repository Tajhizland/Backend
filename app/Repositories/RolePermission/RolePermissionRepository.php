<?php

namespace App\Repositories\RolePermission;

use App\Models\RolePermission;
use App\Repositories\Base\BaseRepository;

class RolePermissionRepository extends BaseRepository implements RolePermissionRepositoryInterface
{
    public function __construct(RolePermission $model)
    {
        parent::__construct($model);
    }

    public function deleteByRole($roleId)
    {
        return $this->model::where("role_id", $roleId)->delete();
    }
}
