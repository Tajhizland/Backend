<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\PopularProduct\PopularProductRequest;
use App\Http\Resources\V1\PopularCategory\PopularCategoryCollection;
use App\Services\PopularProduct\PopularProductServiceInterface;
use Illuminate\Support\Facades\Lang;

class PopularProductController extends Controller
{
    public function __construct(private  PopularProductServiceInterface $popularProductService)
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(new PopularCategoryCollection($this->popularProductService->dataTable()));
    }
    public function add(PopularProductRequest $request)
    {
        $this->popularProductService->add($request->get("product_id"));
        return $this->successResponse(Lang::get("action.add_to",["attr"=>Lang::get("attr.category") , "to"=>Lang::get("attr.list")]));
    }
    public function delete($id)
    {
        $this->popularProductService->delete($id);
        return $this->successResponse(Lang::get("action.remove_from",["attr"=>Lang::get("attr.category") , "from"=>Lang::get("attr.list")]));
    }
}
