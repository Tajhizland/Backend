<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Brand\BrandResource;
use App\Http\Resources\V1\Category\CategoryResource;
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

        return $this->dataResponse([
            "brand" => $brandResource,
            "products" => $productCollection,
        ]);
    }
}
