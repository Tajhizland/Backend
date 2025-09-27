<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Search\SearchRequest;
use App\Http\Resources\V1\Product\ProductCollection;
use App\Http\Resources\V1\Search\SearchCollection;
use App\Services\Search\SearchServiceInterface;

class SearchController extends Controller
{
    public function __construct
    (
        private SearchServiceInterface $searchService
    )
    {
    }

//    public function index(SearchRequest $request)
//    {
//        return $this->dataResponseCollection(new ProductCollection($this->searchService->searchQuery($request->get("query"))));
//    }
    public function index(SearchRequest $request)
    {
        return $this->dataResponse($this->searchService->searchQuery($request->get("query")));
    }

    public function paginate(SearchRequest $request)
    {
        return $this->dataResponseCollection(new ProductCollection($this->searchService->searchPaginate($request->get("query"))));
    }
}
