<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\ProductSortRequest;
use App\Http\Requests\Admin\Category\StoreCategoryRequest;
use App\Http\Requests\Admin\Category\UpdateCategoryRequest;
use App\Http\Requests\Admin\Filter\SetFilterRequest;
use App\Http\Requests\Admin\Option\SetOptionRequest;
use App\Http\Requests\Admin\Option\SortOptionItemRequest;
use App\Http\Requests\Admin\Option\SortOptionRequest;
use App\Http\Requests\Admin\Option\UpdateOptionItemRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Services\Category\CategoryServiceInterface;
use App\Services\Filter\FilterServiceInterface;
use App\Services\Option\OptionServiceInterface;
use App\Http\Resources\Category\SimpleCategoryResource;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\OptionItem\OptionItemResource;
use App\Http\Resources\Filter\FilterResource;
use App\Http\Resources\CategoryList\CategoryListResource;

class CategoryController extends Controller
{
    public function __construct
    (
        private readonly CategoryServiceInterface $categoryService,
        private readonly FilterServiceInterface   $filterService,
        private readonly OptionServiceInterface   $optionService,
    )
    {
    }

    public function list()
    {
        return $this->dataResponseCollection(CategoryListResource::collection($this->categoryService->list()));
    }

    public function productList($id)
    {
        return $this->dataResponseCollection(ProductResource::collection($this->categoryService->productList($id)));
    }

    public function productSort(ProductSortRequest $request)
    {
        $this->categoryService->productSort($request->get("product"));
        return $this->successResponse(__("action.sort", ["attr" => __("attr.category")]));
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(SimpleCategoryResource::collection($this->categoryService->dataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new CategoryResource($this->categoryService->findById($id)));
    }

    public function store(StoreCategoryRequest $request)
    {
        $this->categoryService->storeCategory($request->get("name"), $request->get("status"), $request->get("url"), $request->file("image"), $request->get("description"), $request->get("parent_id"), $request->get("type"));
        return $this->successResponse(__("action.store", ["attr" => __("attr.category")]));
    }

    public function update(UpdateCategoryRequest $request)
    {
        $this->categoryService->updateCategory($request->get("id"), $request->get("name"), $request->get("status"), $request->get("url"), $request->file("image"), $request->get("description"), $request->get("parent_id"), $request->get("type"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.category")]));
    }

    public function getFilter($id)
    {
        return $this->dataResponseCollection(FilterResource::collection($this->filterService->getCategoryFilters($id)));
    }

    public function getOption($id)
    {
        return $this->dataResponseCollection(OptionItemResource::collection($this->optionService->getCategoryOptions($id)));
    }

    public function getOptionItem($id)
    {
        return $this->dataResponseCollection(OptionItemResource::collection($this->optionService->getItemOfOption($id)));
    }

    public function setFilter(SetFilterRequest $request)
    {
        $this->filterService->setFilter($request->get("category_id"), $request->get("filter"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.filter")]));
    }

    public function setOption(SetOptionRequest $request)
    {
        $this->optionService->setOption($request->get("category_id"), $request->get("option"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.option")]));
    }

    public function updateOption(UpdateOptionItemRequest $request)
    {
        $this->optionService->updateOptionItem($request->get("id"), $request->get("categoryId"), $request->get("title"), $request->get("status"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.option")]));
    }
    public function sortOption(SortOptionRequest $request)
    {
        $this->optionService->sortOption($request->get("option"));
        return $this->successResponse(__("action.sort", ["attr" => __("attr.option")]));
    }

    public function sortOptionItem(SortOptionItemRequest $request)
    {
        $this->optionService->sortOptionItem($request->get("optionItem"));
        return $this->successResponse(__("action.sort", ["attr" => __("attr.option")]));
    }

    public function deleteImage($id)
    {
        $this->categoryService->deleteImage($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.image")]));
    }
}
