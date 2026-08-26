<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\StoreRoleRequest;
use App\Http\Requests\Admin\Role\UpdateRoleRequest;
use App\Services\Role\RoleServiceInterface;
use Illuminate\Support\Facades\Lang;
use App\Http\Resources\Role\RoleResource;

class RoleController extends Controller
{
    public function __construct
    (
        private RoleServiceInterface $roleService
    )
    {
    }

    public function dataTable()
    {
        $response = $this->roleService->dataTable();
        return $this->dataResponseCollection(RoleResource::collection($response));
    }

    public function find($id)
    {
        $response = $this->roleService->find($id);
        return $this->dataResponse(RoleResource::make($response));
    }
    public function getAll()
    {
        $response = $this->roleService->getAll();
        return $this->dataResponseCollection(RoleResource::collection($response));
    }

    public function store(StoreRoleRequest $request)
    {
        $this->roleService->store($request->get("name"),$request->get("permissions"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.role")]));
    }

    public function update(UpdateRoleRequest $request)
    {
        $this->roleService->update($request->get("id"), $request->get("name"),$request->get("permissions"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.role")]));

    }
}
