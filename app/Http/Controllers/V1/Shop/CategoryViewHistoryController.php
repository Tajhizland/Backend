<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\CategoryViewHistory\StoreCategoryViewHistoryRequest;
use App\Http\Resources\V1\Product\ProductCollection;
use App\Services\CategoryViewHistory\CategoryViewHistoryService;
use App\Services\CategoryViewHistory\CategoryViewHistoryServiceInterface;
use Illuminate\Support\Facades\Auth;

class CategoryViewHistoryController extends Controller
{
    public function __construct
    (
        private CategoryViewHistoryServiceInterface $categoryViewHistoryService
    )
    {
    }

    public function store(StoreCategoryViewHistoryRequest $request)
    {
        $this->categoryViewHistoryService->store(Auth::user()->id, $request->get("category_id"));
    }

    public function suggest()
    {
        $response = $this->categoryViewHistoryService->suggest(Auth::user()->id);
        return $this->dataResponseCollection(
            new ProductCollection($response)
        );
    }
}
