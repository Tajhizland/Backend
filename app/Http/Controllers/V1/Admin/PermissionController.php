<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Permission\StorePermissionRequest;
use App\Http\Requests\V1\Admin\Permission\UpdatePermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\V1\Permission\PermissionCollection;
use App\Services\Permission\PermissionServiceInterface;
use Illuminate\Support\Facades\Lang;

class PermissionController extends Controller
{
    public function __construct
    (
        private PermissionServiceInterface $permissionService
    )
    {
    }

    public function dataTable()
    {
        $response = $this->permissionService->dataTable();
        return $this->dataResponseCollection(PermissionCollection::make($response));
    }

    public function find($id)
    {
        $response = $this->permissionService->find($id);
        return $this->dataResponse(PermissionResource::make($response));
    }
    public function getAll()
    {
        $response = $this->permissionService->getAll();
        return $this->dataResponseCollection(PermissionCollection::make($response));
    }

    public function store(StorePermissionRequest $request)
    {
        $this->permissionService->store($request->get("name"), $request->get("value"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.permission")]));
    }

    public function update(UpdatePermissionRequest $request)
    {
        $this->permissionService->update($request->get("id"), $request->get("name"), $request->get("value"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.permission")]));

    }
}
