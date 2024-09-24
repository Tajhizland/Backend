<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\PopularCategory\PopularCategoryRequest;
use App\Http\Resources\V1\PopularCategory\PopularCategoryCollection;
use App\Services\PopularCategory\PopularCategoryServiceInterface;
use Illuminate\Support\Facades\Lang;

class PopularCategoryController extends Controller
{
    public function __construct(private  PopularCategoryServiceInterface $popularCategoryService)
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(new PopularCategoryCollection($this->popularCategoryService->dataTable()));
    }
    public function add(PopularCategoryRequest $request)
    {
        $this->popularCategoryService->add($request->get("category_id"));
        return $this->successResponse(Lang::get("action.add_to",["attr"=>Lang::get("attr.category") , "to"=>Lang::get("attr.list")]));
    }
    public function delete($id)
    {
        $this->popularCategoryService->delete($id);
        return $this->successResponse(Lang::get("action.remove_from",["attr"=>Lang::get("attr.category") , "from"=>Lang::get("attr.list")]));
    }
}
