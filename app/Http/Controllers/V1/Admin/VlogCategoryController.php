<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\VlogCategory\StoreVlogCategoryRequest;
use App\Http\Requests\V1\Admin\VlogCategory\UpdateVlogCategoryRequest;
use App\Http\Resources\V1\VlogCategory\VlogCategoryCollection;
use App\Http\Resources\V1\VlogCategory\VlogCategoryResource;
use App\Http\Requests\V1\Admin\VlogCategory\VlogCategorySortRequest;
use App\Services\VlogCategory\VlogCategoryServiceInterface;
use Illuminate\Support\Facades\Lang;

class VlogCategoryController extends Controller
{
    public function __construct
    (
        private VlogCategoryServiceInterface $vlogCategoryService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(new VlogCategoryCollection($this->vlogCategoryService->dataTable()));
    }

    public function list()
    {
        return $this->dataResponseCollection(new VlogCategoryCollection($this->vlogCategoryService->getActiveList()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new VlogCategoryResource($this->vlogCategoryService->findById($id)));
    }

    public function store(StoreVlogCategoryRequest $request)
    {
        $this->vlogCategoryService->store($request->get("name"), $request->get("status"), $request->get("url"), $request->file("icon"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.category")]));
    }

    public function update(UpdateVlogCategoryRequest $request)
    {
        $this->vlogCategoryService->update($request->get("id"), $request->get("name"), $request->get("status"), $request->get("url"), $request->file("icon"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.category")]));
    }
    public function sort(VlogCategorySortRequest $request)
    {
        $this->vlogCategoryService->sort($request->get("vlogs"));
        return $this->successResponse(Lang::get("action.sort", ["attr" => Lang::get("attr.category")]));
    }
}
