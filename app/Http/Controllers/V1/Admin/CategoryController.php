<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Category\ProductSortRequest;
use App\Http\Requests\V1\Admin\Category\StoreCategoryRequest;
use App\Http\Requests\V1\Admin\Category\UpdateCategoryRequest;
use App\Http\Requests\V1\Admin\Filter\SetFilterRequest;
use App\Http\Requests\V1\Admin\Option\SetOptionRequest;
use App\Http\Resources\V1\Category\CategoryCollection;
use App\Http\Resources\V1\Category\CategoryResource;
use App\Http\Resources\V1\CategoryList\CategoryListCollection;
use App\Http\Resources\V1\Filter\FilterCollection;
use App\Http\Resources\V1\Option\OptionCollection;
use App\Http\Resources\V1\Product\ProductCollection;
use App\Services\Category\CategoryServiceInterface;
use App\Services\Filter\FilterServiceInterface;
use App\Services\Option\OptionServiceInterface;
use Illuminate\Support\Facades\Lang;

class CategoryController extends Controller
{
    public function __construct
    (
        private CategoryServiceInterface $categoryService,
        private FilterServiceInterface   $filterService,
        private OptionServiceInterface   $optionService,
    )
    {
    }

    public function list()
    {
        return $this->dataResponseCollection(new CategoryListCollection($this->categoryService->list()));
    }

    public function productList($id)
    {
        return $this->dataResponseCollection(new ProductCollection($this->categoryService->productList($id)));
    }
    public function productSort(ProductSortRequest $request)
    {
        $this->categoryService->productSort($request->get("product"));
        return $this->successResponse(Lang::get("action.sort", ["attr" => Lang::get("attr.category")]));
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(new CategoryCollection($this->categoryService->dataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new CategoryResource($this->categoryService->findById($id)));
    }

    public function store(StoreCategoryRequest $request)
    {
        $this->categoryService->storeCategory($request->get("name"), $request->get("status"), $request->get("url"), $request->file("image"), $request->get("description"), $request->get("parent_id"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.category")]));
    }

    public function update(UpdateCategoryRequest $request)
    {
        $this->categoryService->updateCategory($request->get("id"), $request->get("name"), $request->get("status"), $request->get("url"), $request->file("image"), $request->get("description"), $request->get("parent_id"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.category")]));
    }

    public function getFilter($id)
    {
        return $this->dataResponseCollection(new FilterCollection($this->filterService->getCategoryFilters($id)));
    }

    public function getOption($id)
    {
        return $this->dataResponseCollection(new OptionCollection($this->optionService->getCategoryOptions($id)));
    }

    public function setFilter(SetFilterRequest $request)
    {
        $this->filterService->setFilter($request->get("category_id"), $request->get("filter"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.filter")]));
    }

    public function setOption(SetOptionRequest $request)
    {
        $this->optionService->setOption($request->get("category_id"), $request->get("option"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.option")]));
    }
}
