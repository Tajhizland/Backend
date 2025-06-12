<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\CategoryViewHistory\StoreCategoryViewHistoryRequest;
use App\Services\CategoryViewHistory\CategoryViewHistoryService;
use Illuminate\Support\Facades\Auth;

class CategoryViewHistoryController extends Controller
{
    public function __construct
    (
        private CategoryViewHistoryService $categoryViewHistoryService
    )
    {
    }

    public function store(StoreCategoryViewHistoryRequest $request)
    {
        $this->categoryViewHistoryService->store(Auth::user()->id, $request->get("categoryId"));
    }
}
