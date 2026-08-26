<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\PopularProduct\PopularProductAddDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PopularProduct\PopularProductRequest;
use App\Services\PopularProduct\PopularProductServiceInterface;
use App\Http\Resources\PopularProduct\PopularProductResource;

class PopularProductController extends Controller
{
    public function __construct(private readonly PopularProductServiceInterface $popularProductService)
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(PopularProductResource::collection($this->popularProductService->dataTable()));
    }
    public function store(PopularProductRequest $request)
    {
        $this->popularProductService->add(new PopularProductAddDto(...$request->validated()));
        return $this->successResponse(__("action.add_to",["attr"=>__("attr.category") , "to"=>__("attr.list")]));
    }
    public function destroy($id)
    {
        $this->popularProductService->delete($id);
        return $this->successResponse(__("action.remove_from",["attr"=>__("attr.category") , "from"=>__("attr.list")]));
    }
}
