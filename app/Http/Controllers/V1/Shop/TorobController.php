<?php

namespace App\Http\Controllers\V1\Shop;

use App\DTOs\Torob\TorobListDto;
use App\Http\Requests\Shop\Torob\TorobListRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\Torob\NewTorobResource; 
use App\Services\Product\ProductServiceInterface;

class TorobController extends Controller
{
    public function __construct
    (
        private readonly ProductServiceInterface $productService
    )
    {
    }


    public function list(TorobListRequest $request)
    {
        $dto = new TorobListDto(...$request->validated());
        $page_urls = $dto->page_urls;
        $page_uniques = $dto->page_uniques;
        $page = $dto->page;
        $sort = $dto->sort;
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
