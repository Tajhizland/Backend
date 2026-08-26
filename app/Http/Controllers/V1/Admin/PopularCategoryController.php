<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PopularCategory\PopularCategoryRequest;
use App\Services\PopularCategory\PopularCategoryServiceInterface;
use App\Http\Resources\PopularCategory\PopularCategoryResource;

class PopularCategoryController extends Controller
{
    public function __construct(private readonly PopularCategoryServiceInterface $popularCategoryService)
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(PopularCategoryResource::collection($this->popularCategoryService->dataTable()));
    }
    public function add(PopularCategoryRequest $request)
    {
        $this->popularCategoryService->add($request->get("category_id"));
        return $this->successResponse(__("action.add_to",["attr"=>__("attr.category") , "to"=>__("attr.list")]));
    }
    public function delete($id)
    {
        $this->popularCategoryService->delete($id);
        return $this->successResponse(__("action.remove_from",["attr"=>__("attr.category") , "from"=>__("attr.list")]));
    }
}
