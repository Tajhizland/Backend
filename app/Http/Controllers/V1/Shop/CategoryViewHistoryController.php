<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\CategoryViewHistory\StoreCategoryViewHistoryRequest;
use App\Services\CategoryViewHistory\CategoryViewHistoryService;
use App\Services\CategoryViewHistory\CategoryViewHistoryServiceInterface;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Product\ProductResource;

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
        $userId = Auth::id();
        $ip = request()->ip();
        $this->categoryViewHistoryService->store($userId, $ip, $request->get("category_id"));
    }

    public function suggest()
    {
        $response = $this->categoryViewHistoryService->suggest(Auth::user()->id);
        return $this->dataResponseCollection(
            ProductResource::collection($response)
        );
    }
    public function suggestIp()
    {
        $response = $this->categoryViewHistoryService->suggestIp(request()->ip());
        return $this->dataResponseCollection(
            ProductResource::collection($response)
        );
    }
}
