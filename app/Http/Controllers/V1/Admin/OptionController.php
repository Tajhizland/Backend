<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Option\OptionStoreDto;
use App\DTOs\Option\OptionUpdateDto;
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

    public function show($id)
    {
        return $this->dataResponse(new OptionResource($this->optionService->find($id)));
    }

    public function store(StoreOptionRequest $request)
    {
        $this->optionService->store(new OptionStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store",["attr"=>__("attr.option")]));
     }

    public function update($id, UpdateOptionRequest $request)
    {
        $this->optionService->update(new OptionUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update",["attr"=>__("attr.option")]));
    }
}
