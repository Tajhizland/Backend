<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Brand\BrandSortDto;
use App\DTOs\Brand\BrandStoreDto;
use App\DTOs\Brand\BrandUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Brand\BrandSortRequest;
use App\Http\Requests\Admin\Brand\StoreBrandRequest;
use App\Http\Requests\Admin\Brand\UpdateBrandRequest;
use App\Http\Resources\Brand\BrandResource;
use App\Services\Brand\BrandServiceInterface;

class BrandController extends Controller
{
    public function __construct(
        private readonly BrandServiceInterface $brandService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(BrandResource::collection($this->brandService->dataTable()));
    }

    public function list()
    {
        return $this->dataResponseCollection(BrandResource::collection($this->brandService->list()));
    }

    public function sort(BrandSortRequest $request)
    {
        $dto = new BrandSortDto(...$request->validated());
        $this->brandService->sort($dto);
        return $this->successResponse(__("action.sort", ["attr" => __("attr.brand")]));
    }

    public function show($id)
    {
        return $this->dataResponse(new BrandResource($this->brandService->find($id)));
    }

    public function store(StoreBrandRequest $request)
    {
        $dto = new BrandStoreDto(...$request->validated());
        $this->brandService->store($dto);
        return $this->successResponse(__("action.store", ["attr" => __("attr.brand")]));
    }

    public function update($id, UpdateBrandRequest $request)
    {
        $dto = new BrandUpdateDto($id, ...$request->validated());
        $this->brandService->update($dto);
        return $this->successResponse(__("action.update", ["attr" => __("attr.brand")]));
    }
}
