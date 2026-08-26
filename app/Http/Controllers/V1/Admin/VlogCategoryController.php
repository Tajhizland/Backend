<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VlogCategory\StoreVlogCategoryRequest;
use App\Http\Requests\Admin\VlogCategory\UpdateVlogCategoryRequest;
use App\Http\Resources\VlogCategory\VlogCategoryResource;
use App\Http\Requests\Admin\VlogCategory\VlogCategorySortRequest;
use App\Services\VlogCategory\VlogCategoryServiceInterface;

class VlogCategoryController extends Controller
{
    public function __construct
    (
        private readonly VlogCategoryServiceInterface $vlogCategoryService
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

    public function findById($id)
    {
        return $this->dataResponse(new VlogCategoryResource($this->vlogCategoryService->findById($id)));
    }

    public function store(StoreVlogCategoryRequest $request)
    {
        $this->vlogCategoryService->store($request->get("name"), $request->get("status"), $request->get("url"), $request->file("icon"));
        return $this->successResponse(__("action.store", ["attr" => __("attr.category")]));
    }

    public function update(UpdateVlogCategoryRequest $request)
    {
        $this->vlogCategoryService->update($request->get("id"), $request->get("name"), $request->get("status"), $request->get("url"), $request->file("icon"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.category")]));
    }
    public function sort(VlogCategorySortRequest $request)
    {
        $this->vlogCategoryService->sort($request->get("vlogs"));
        return $this->successResponse(__("action.sort", ["attr" => __("attr.category")]));
    }
}
