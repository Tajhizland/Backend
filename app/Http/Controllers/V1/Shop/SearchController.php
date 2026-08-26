<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\Search\SearchRequest;
use App\Services\Search\SearchServiceInterface;
use App\Http\Resources\Product\ProductResource;

class SearchController extends Controller
{
    public function __construct
    (
        private readonly SearchServiceInterface $searchService
    )
    {
    }

//    public function index(SearchRequest $request)
//    {
//        return $this->dataResponseCollection(ProductResource::collection($this->searchService->searchQuery($request->get("query"))));
//    }
    public function index(SearchRequest $request)
    {
        return $this->dataResponse($this->searchService->searchQuery($request->get("query")));
    }

    public function paginate(SearchRequest $request)
    {
        return $this->dataResponseCollection(ProductResource::collection($this->searchService->searchPaginate($request->get("query"))));
    }
}
