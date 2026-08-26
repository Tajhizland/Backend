<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\CastCategory\CastCategoryStoreDto;
use App\DTOs\CastCategory\CastCategoryUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CastCategory\StoreCastCategoryRequest;
use App\Http\Requests\Admin\CastCategory\UpdateCastCategoryRequest;
use App\Http\Resources\CastCategory\CastCategoryResource;
use App\Services\CastCategory\CastCategoryServiceInterface;

class CastCategoryController extends Controller
{
    public function __construct(
        private readonly CastCategoryServiceInterface $castCategoryService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(CastCategoryResource::collection($this->castCategoryService->dataTable()));
    }

    public function get()
    {
        return $this->dataResponseCollection(CastCategoryResource::collection($this->castCategoryService->get()));
    }

    public function show($id)
    {
        return $this->dataResponse(new CastCategoryResource($this->castCategoryService->find($id)));
    }

    public function store(StoreCastCategoryRequest $request)
    {
        $this->castCategoryService->store(new CastCategoryStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.category")]));
    }

    public function update($id, UpdateCastCategoryRequest $request)
    {
        $this->castCategoryService->update(new CastCategoryUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.category")]));
    }
}
