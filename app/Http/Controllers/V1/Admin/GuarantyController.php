<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Guaranty\StoreGuarantyRequest;
use App\Http\Requests\Admin\Guaranty\UpdateGuarantyRequest;
use App\Http\Resources\Guaranty\GuarantyResource;
use App\Services\Guaranty\GuarantyServiceInterface;

class GuarantyController extends Controller
{
    public function __construct
    (
        private readonly GuarantyServiceInterface $guarantyService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(GuarantyResource::collection($this->guarantyService->dataTable()));
    }
    public function list()
    {
        return $this->dataResponseCollection(GuarantyResource::collection($this->guarantyService->getActives()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new GuarantyResource($this->guarantyService->findById($id)));
    }

    public function store(StoreGuarantyRequest $request)
    {
        $this->guarantyService->store($request->get("name"),$request->get("free"), $request->get("description"), $request->file("icon"), $request->get("status"),$request->get("url"));
        return $this->successResponse(__("action.store", ["attr" => __("attr.guaranty")]));
    }

    public function update(UpdateGuarantyRequest $request)
    {
        $this->guarantyService->update($request->get("id"), $request->get("name"), $request->get("free"), $request->get("description"), $request->file("icon"), $request->get("status"),$request->get("url"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.guaranty")]));
    }
}
