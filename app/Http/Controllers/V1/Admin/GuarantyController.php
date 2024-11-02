<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Guaranty\StoreGuarantyRequest;
use App\Http\Requests\V1\Admin\Guaranty\UpdateGuarantyRequest;
use App\Http\Resources\V1\Guaranty\GuarantyCollection;
use App\Http\Resources\V1\Guaranty\GuarantyResource;
use App\Services\Guaranty\GuarantyServiceInterface;
use Illuminate\Support\Facades\Lang;

class GuarantyController extends Controller
{
    public function __construct
    (
        private GuarantyServiceInterface $guarantyService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(new GuarantyCollection($this->guarantyService->dataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new GuarantyResource($this->guarantyService->findById($id)));
    }

    public function store(StoreGuarantyRequest $request)
    {
        $this->guarantyService->store($request->get("name"), $request->get("description"), $request->file("icon"), $request->get("status"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.guaranty")]));
    }

    public function update(UpdateGuarantyRequest $request)
    {
        $this->guarantyService->update($request->get("id"), $request->get("name"), $request->get("description"), $request->file("icon"), $request->get("status"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.guaranty")]));
    }
}
