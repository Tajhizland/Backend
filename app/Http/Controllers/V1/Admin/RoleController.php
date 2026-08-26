<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Role\RoleStoreDto;
use App\DTOs\Role\RoleUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\StoreRoleRequest;
use App\Http\Requests\Admin\Role\UpdateRoleRequest;
use App\Http\Resources\Role\RoleResource;
use App\Services\Role\RoleServiceInterface;

class RoleController extends Controller
{
    public function __construct(
        private readonly RoleServiceInterface $roleService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(RoleResource::collection($this->roleService->dataTable()));
    }

    public function getAll()
    {
        return $this->dataResponseCollection(RoleResource::collection($this->roleService->getAll()));
    }

    public function show($id)
    {
        return $this->dataResponse(RoleResource::make($this->roleService->find($id)));
    }

    public function store(StoreRoleRequest $request)
    {
        $this->roleService->store(new RoleStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.role")]));
    }

    public function update($id, UpdateRoleRequest $request)
    {
        $this->roleService->update(new RoleUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.role")]));
    }
}
