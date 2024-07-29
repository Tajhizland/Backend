<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\SearchRequest;
use App\Http\Resources\V1\Search\SearchCollection;
use App\Services\Search\SearchServiceInterface;

class SearchController extends Controller
{
    public function __construct
    (
        private SearchServiceInterface $searchService
    ) {}

    public function index(SearchRequest $request)
    {
        return $this->dataResponse(new SearchCollection($this->searchService->searchQuery($request->get("query"))));
    }
}
