<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Permission\StorePermissionRequest;
use App\Http\Requests\Admin\Permission\UpdatePermissionRequest;
use App\Services\Permission\PermissionServiceInterface;
use App\Http\Resources\Permission\PermissionResource;

class PermissionController extends Controller
{
    public function __construct
    (
        private readonly PermissionServiceInterface $permissionService
    )
    {
    }

    public function dataTable()
    {
        $response = $this->permissionService->dataTable();
        return $this->dataResponseCollection(PermissionResource::collection($response));
    }

    public function find($id)
    {
        $response = $this->permissionService->find($id);
        return $this->dataResponse(PermissionResource::make($response));
    }
    public function getAll()
    {
        $response = $this->permissionService->getAll();
        return $this->dataResponseCollection(PermissionResource::collection($response));
    }

    public function store(StorePermissionRequest $request)
    {
        $this->permissionService->store($request->get("name"), $request->get("value"));
        return $this->successResponse(__("action.store", ["attr" => __("attr.permission")]));
    }

    public function update(UpdatePermissionRequest $request)
    {
        $this->permissionService->update($request->get("id"), $request->get("name"), $request->get("value"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.permission")]));

    }
}
