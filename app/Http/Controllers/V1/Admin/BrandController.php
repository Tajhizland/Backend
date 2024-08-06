<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\Brand\BrandServiceInterface;

class BrandController extends Controller
{
    public function __construct
    (
        private  BrandServiceInterface $brandService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponse(new CategoryCollection($this->brandService->dataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new CategoryResource($this->brandService->findById($id)));
    }

    public function store(StoreCategoryRequest $request)
    {
        $this->brandService->storeCategory($request->get("name"),$request->get("status"),$request->get("url"),$request->get("image"),$request->get("description"),$request->get("parent_id"));
        return $this->successResponse(Lang::get("responses.category_store_success"));
    }

    public function update(UpdateCategoryRequest $request)
    {
        $this->brandService->updateCategory($request->get("id"),$request->get("name"),$request->get("status"),$request->get("url"),$request->get("image"),$request->get("description"),$request->get("parent_id"));
        return $this->successResponse(Lang::get("responses.category_update_success"));
    }
}
