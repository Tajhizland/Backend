<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Services\CastCategory\CastCategoryService;
use App\Http\Resources\CastCategory\CastCategoryResource;

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
        return $this->dataResponseCollection(CastCategoryResource::collection($response));
    }
}
