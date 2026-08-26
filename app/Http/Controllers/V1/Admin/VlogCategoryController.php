<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\VlogCategory\VlogCategorySortDto;
use App\DTOs\VlogCategory\VlogCategoryStoreDto;
use App\DTOs\VlogCategory\VlogCategoryUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VlogCategory\StoreVlogCategoryRequest;
use App\Http\Requests\Admin\VlogCategory\UpdateVlogCategoryRequest;
use App\Http\Requests\Admin\VlogCategory\VlogCategorySortRequest;
use App\Http\Resources\VlogCategory\VlogCategoryResource;
use App\Services\VlogCategory\VlogCategoryServiceInterface;

class VlogCategoryController extends Controller
{
    public function __construct(
        private readonly VlogCategoryServiceInterface $vlogCategoryService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(VlogCategoryResource::collection($this->vlogCategoryService->dataTable()));
    }

    public function list()
    {
        return $this->dataResponseCollection(VlogCategoryResource::collection($this->vlogCategoryService->getActiveList()));
    }

    public function show($id)
    {
        return $this->dataResponse(new VlogCategoryResource($this->vlogCategoryService->find($id)));
    }

    public function store(StoreVlogCategoryRequest $request)
    {
        $this->vlogCategoryService->store(new VlogCategoryStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.category")]));
    }

    public function update($id, UpdateVlogCategoryRequest $request)
    {
        $this->vlogCategoryService->update(new VlogCategoryUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.category")]));
    }

    public function sort(VlogCategorySortRequest $request)
    {
        $this->vlogCategoryService->sort(new VlogCategorySortDto(...$request->validated()));
        return $this->successResponse(__("action.sort", ["attr" => __("attr.category")]));
    }
}
