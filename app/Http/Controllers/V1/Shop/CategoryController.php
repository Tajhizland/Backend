<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Category\CategoryResource;
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
        $productCollection = new SimpleProductCollection($listing["products"]);

        return $this->dataResponse([
            "category" => $categoryResource,
            "products" => $productCollection,
        ]);
    }
}
