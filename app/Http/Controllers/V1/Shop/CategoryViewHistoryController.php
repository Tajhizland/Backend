<?php

namespace App\Http\Controllers\V1\Shop;

use App\DTOs\CategoryViewHistory\CategoryViewHistoryStoreDto;
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
        private readonly CategoryViewHistoryServiceInterface $categoryViewHistoryService
    )
    {
    }

    public function store(StoreCategoryViewHistoryRequest $request)
    {
        $this->categoryViewHistoryService->store(new CategoryViewHistoryStoreDto(Auth::id(), $request->ip(), ...$request->validated()));
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
