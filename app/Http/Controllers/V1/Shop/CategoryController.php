<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category\CategoryResource;
use App\Services\Category\CategoryServiceInterface;
use Illuminate\Http\Request;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\ProductCategory\ProductCategoryResource;
use App\Http\Resources\Category\SimpleCategoryResource;
use App\Http\Resources\Breadcrumb\BreadcrumbResource;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryServiceInterface $categoryService,
    )
    {
    }

    public function index(Request $request)
    {
        $listing = $this->categoryService->listing($request->get("url"), $request->get("filter"));

        $categoryResource = new CategoryResource($listing["category"]);
        $children = SimpleCategoryResource::collection($listing["children"])->response()->getData();
        $productCollection = ProductCategoryResource::collection($listing["products"])->response()->getData();
        $groups = ProductCategoryResource::collection($listing["groups"])->response()->getData();
        $breadcrumbCollection = BreadcrumbResource::collection($listing["breadcrumb"])->response()->getData();

        return $this->dataResponse([
            "category" => $categoryResource,
            "products" => $productCollection,
            "groups" => $groups,
            "children" => $children,
            "breadcrumb" => $breadcrumbCollection,
        ]);
    }

    public function groupListing(Request $request)
    {
        $listing = $this->categoryService->groupListing($request->get("url"));
        $categoryResource = new CategoryResource($listing["category"]);
        $groups = ProductResource::collection($listing["groups"])->response()->getData();
        $breadcrumbCollection = BreadcrumbResource::collection($listing["breadcrumb"])->response()->getData();

        return $this->dataResponse([
            "category" => $categoryResource,
            "groups" => $groups,
            "breadcrumb" => $breadcrumbCollection,
        ]);
    }
}
