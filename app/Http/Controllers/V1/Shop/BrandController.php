<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Brand\BrandCollection;
use App\Http\Resources\V1\Brand\BrandResource;
use App\Http\Resources\V1\Category\SimpleCategoryCollection;
use App\Http\Resources\V1\Product\ProductCollection;
use App\Services\Brand\BrandServiceInterface;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct
    (
        private BrandServiceInterface $brandService
    )
    {
    }

    public function index(Request $request)
    {
        $listing = $this->brandService->listing($request->get("url"), $request->get("filter"));

        $brandResource = new BrandResource($listing["brand"]);
        $productCollection = new ProductCollection($listing["products"]);
        $categoryCollection = $listing["categories"] ? new SimpleCategoryCollection($listing["categories"]) : $listing["categories"];

        return $this->dataResponse([
            "brand" => $brandResource,
            "products" => $productCollection,
            "categories" => $categoryCollection,
        ]);
    }

    public function list()
    {
        return $this->dataResponseCollection(new BrandCollection($this->brandService->list()));
    }
}
