<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Role\StoreRoleRequest;
use App\Http\Requests\V1\Admin\Role\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Http\Resources\V1\Role\RoleCollection;
use App\Services\Role\RoleServiceInterface;
use Illuminate\Support\Facades\Lang;

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
        return $this->dataResponseCollection(RoleCollection::make($response));
    }

    public function find($id)
    {
        $response = $this->roleService->find($id);
        return $this->dataResponse(RoleResource::make($response));
    }
    public function getAll()
    {
        $response = $this->roleService->getAll();
        return $this->dataResponseCollection(RoleCollection::make($response));
    }

    public function store(StoreRoleRequest $request)
    {
        $this->roleService->store($request->get("name"),$request->get("permission"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.role")]));
    }

    public function update(UpdateRoleRequest $request)
    {
        $this->roleService->update($request->get("id"), $request->get("name"),$request->get("permission"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.role")]));

    }
}
