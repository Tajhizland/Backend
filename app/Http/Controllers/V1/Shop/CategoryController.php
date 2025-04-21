<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Breadcrumb\BreadcrumbCollection;
use App\Http\Resources\V1\Category\CategoryCollection;
use App\Http\Resources\V1\Category\CategoryResource;
use App\Http\Resources\V1\Product\ProductCollection;
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
        $children = new CategoryCollection($listing["children"]);
        $productCollection = new ProductCollection($listing["products"]);
        $breadcrumbCollection = new BreadcrumbCollection($listing["breadcrumb"]);

        return $this->dataResponse([
            "category" => $categoryResource,
            "products" => $productCollection,
            "children" => $children,
            "breadcrumb" => $breadcrumbCollection,
        ]);
    }
}
