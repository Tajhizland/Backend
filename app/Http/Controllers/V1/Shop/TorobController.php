<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Torob\NewTorobResource; 
use App\Services\Product\ProductServiceInterface;
use Illuminate\Http\Request;

class TorobController extends Controller
{
    public function __construct
    (
        private ProductServiceInterface $productService
    )
    {
    }


    public function list(Request $request)
    {
        $page_urls = $request->get("page_urls");
        $page_uniques = $request->get("page_uniques");
        $page = $request->get("page");
        $sort = $request->get("sort");
        $response = $this->productService->torobProduct($page_urls, $page_uniques, $page, $sort);

        $total = $response->total();
        $currentPage = $response->currentPage();
        $lastPage = $response->lastPage();
        $response = [
            "api_version" => "torob_api_v3",
            "total" => $total,
            "current_page" => $currentPage,
            "max_pages" => $lastPage,
            "products" => NewTorobResource::collection($response),
        ];
        return $response;
    }
}
