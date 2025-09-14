<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Breadcrumb\BreadcrumbCollection;
use App\Http\Resources\V1\Category\CategoryResource;
use App\Http\Resources\V1\Category\SimpleCategoryCollection;
use App\Http\Resources\V1\Product\ProductCollection;
use App\Http\Resources\V1\Product\SimpleProduct\SimpleProductCollection;
use App\Services\Category\CategoryServiceInterface;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryServiceInterface $categoryService,
    )
    {
    }

    public function index(Request $request)
    {
        $listing = $this->categoryService->listing($request->get("url"), $request->get("filter"));

        $categoryResource = new CategoryResource($listing["category"]);
        $children = new SimpleCategoryCollection($listing["children"]);
        $productCollection = new SimpleProductCollection($listing["products"]);
        $groups = new ProductCollection($listing["groups"]);
        $breadcrumbCollection = new BreadcrumbCollection($listing["breadcrumb"]);

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
        $groups = new ProductCollection($listing["groups"]);
        $breadcrumbCollection = new BreadcrumbCollection($listing["breadcrumb"]);

        return $this->dataResponse([
            "category" => $categoryResource,
            "groups" => $groups,
            "breadcrumb" => $breadcrumbCollection,
        ]);
    }
}
