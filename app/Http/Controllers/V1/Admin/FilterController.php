<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Filter\FilterStoreDto;
use App\DTOs\Filter\FilterUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Filter\StoreFilterRequest;
use App\Http\Requests\Admin\Filter\UpdateFilterRequest;
use App\Http\Resources\Filter\FilterResource;
use App\Services\Filter\FilterServiceInterface;

class FilterController extends Controller
{
    public function __construct
    (
        private readonly FilterServiceInterface $filterService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(FilterResource::collection($this->filterService->dataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new FilterResource($this->filterService->findById($id)));
    }

    public function store(StoreFilterRequest $request)
    {
        $this->filterService->createFilter(new FilterStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store",["attr"=>__("attr.filter")]));
     }

    public function update($id, UpdateFilterRequest $request)
    {
        $this->filterService->updateFilter(new FilterUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update",["attr"=>__("attr.filter")]));
    }
}
