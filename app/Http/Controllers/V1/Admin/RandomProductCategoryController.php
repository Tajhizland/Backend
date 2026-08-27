<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\RandomProductCategory\RandomProductCategoryAddDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RandomProductCategory\RandomProductCategoryRequest;
use App\Http\Resources\RandomProductCategory\RandomProductCategoryResource;
use App\Services\RandomProductCategory\RandomProductCategoryServiceInterface;

class RandomProductCategoryController extends Controller
{
    public function __construct(
        private readonly RandomProductCategoryServiceInterface $randomProductCategoryService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(RandomProductCategoryResource::collection($this->randomProductCategoryService->dataTable()));
    }

    public function store(RandomProductCategoryRequest $request)
    {
        $this->randomProductCategoryService->add(new RandomProductCategoryAddDto(...$request->validated()));
        return $this->successResponse(__("action.add_to", ["attr" => __("attr.category"), "to" => __("attr.list")]));
    }

    public function destroy($id)
    {
        $this->randomProductCategoryService->delete($id);
        return $this->successResponse(__("action.remove_from", ["attr" => __("attr.category"), "from" => __("attr.list")]));
    }
}
