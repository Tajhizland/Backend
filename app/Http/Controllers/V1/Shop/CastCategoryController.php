<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CastCategory\CastCategoryCollection;
use App\Services\CastCategory\CastCategoryService;

class CastCategoryController extends Controller
{
    public function __construct
    (
        private CastCategoryService $castCategoryService
    )
    {
    }

    public function index()
    {
        $response = $this->castCategoryService->get();
        return $this->dataResponseCollection(CastCategoryCollection::make($response));
    }
}
