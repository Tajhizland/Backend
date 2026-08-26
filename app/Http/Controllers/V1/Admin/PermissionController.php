<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Permission\PermissionStoreDto;
use App\DTOs\Permission\PermissionUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Permission\StorePermissionRequest;
use App\Http\Requests\Admin\Permission\UpdatePermissionRequest;
use App\Http\Resources\Permission\PermissionResource;
use App\Services\Permission\PermissionServiceInterface;

class PermissionController extends Controller
{
    public function __construct(
        private readonly PermissionServiceInterface $permissionService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(PermissionResource::collection($this->permissionService->dataTable()));
    }

    public function getAll()
    {
        return $this->dataResponseCollection(PermissionResource::collection($this->permissionService->getAll()));
    }

    public function show($id)
    {
        return $this->dataResponse(PermissionResource::make($this->permissionService->find($id)));
    }

    public function store(StorePermissionRequest $request)
    {
        $this->permissionService->store(new PermissionStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.permission")]));
    }

    public function update($id, UpdatePermissionRequest $request)
    {
        $this->permissionService->update(new PermissionUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.permission")]));
    }
}
