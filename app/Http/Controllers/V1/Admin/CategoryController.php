<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Category\StoreCategoryRequest;
use App\Http\Requests\V1\Admin\Category\UpdateCategoryRequest;
use App\Http\Resources\V1\Category\CategoryCollection;
use App\Http\Resources\V1\Category\CategoryResource;
use App\Services\Category\CategoryServiceInterface;
use Illuminate\Support\Facades\Lang;

class CategoryController extends Controller
{
    public function __construct
    (
        private  CategoryServiceInterface $categoryService
    )
    {
    }

    public function list()
    {
        return $this->dataResponse(new CategoryCollection($this->categoryService->list()));
    }
    public function dataTable()
    {
        return $this->dataResponse(new CategoryCollection($this->categoryService->dataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new CategoryResource($this->categoryService->findById($id)));
    }

    public function store(StoreCategoryRequest $request)
    {
        $this->categoryService->storeCategory($request->get("name"),$request->get("status"),$request->get("url"),$request->get("image"),$request->get("description"),$request->get("parent_id"));
        return $this->successResponse(Lang::get("action.store",["attr"=>Lang::get("attr.category")]));
    }

    public function update(UpdateCategoryRequest $request)
    {
        $this->categoryService->updateCategory($request->get("id"),$request->get("name"),$request->get("status"),$request->get("url"),$request->get("image"),$request->get("description"),$request->get("parent_id"));
        return $this->successResponse(Lang::get("action.update",["attr"=>Lang::get("attr.category")]));
    }
}
