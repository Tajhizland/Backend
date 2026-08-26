<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Option\StoreOptionRequest;
use App\Http\Requests\Admin\Option\UpdateOptionRequest;
use App\Http\Resources\Option\OptionResource;
use App\Services\Option\OptionServiceInterface;

class OptionController extends Controller
{
    public function __construct
    (
        private readonly OptionServiceInterface $optionService
    )
    {}
    public function dataTable()
    {
        return $this->dataResponseCollection(OptionResource::collection($this->optionService->dataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new OptionResource($this->optionService->findById($id)));
    }

    public function store(StoreOptionRequest $request)
    {
        $this->optionService->createOption($request->get("title"),$request->get("category_id"),$request->get("status") ,$request->get("items"));
        return $this->successResponse(__("action.store",["attr"=>__("attr.option")]));
     }

    public function update(UpdateOptionRequest $request)
    {
        $this->optionService->updateOption($request->get("id"),$request->get("title"),$request->get("category_id"),$request->get("status") ,$request->get("items"));
        return $this->successResponse(__("action.update",["attr"=>__("attr.option")]));
    }
}
