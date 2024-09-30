<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\PopularProduct\PopularProductRequest;
use App\Http\Resources\V1\SpecialProduct\SpecialProductCollection;
use App\Services\SpecialProduct\SpecialProductServiceInterface;
use Illuminate\Support\Facades\Lang;

class SpecialProductController extends Controller
{
    public function __construct(private  SpecialProductServiceInterface $specialProductService)
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(new SpecialProductCollection($this->specialProductService->dataTable()));
    }
    public function add(PopularProductRequest $request)
    {
        $this->specialProductService->add($request->get("product_id"));
        return $this->successResponse(Lang::get("action.add_to",["attr"=>Lang::get("attr.category") , "to"=>Lang::get("attr.list")]));
    }
    public function delete($id)
    {
        $this->specialProductService->delete($id);
        return $this->successResponse(Lang::get("action.remove_from",["attr"=>Lang::get("attr.category") , "from"=>Lang::get("attr.list")]));
    }
}
