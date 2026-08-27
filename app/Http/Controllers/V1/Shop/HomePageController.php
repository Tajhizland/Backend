<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Services\HomePage\HomePageServiceInterface;
use Illuminate\Http\JsonResponse;

class HomePageController extends Controller
{
    public function __construct
    (
        private readonly HomePageServiceInterface $homePageService
    )
    {
    }

    public function index(): JsonResponse
    {
        return $this->dataResponse($this->homePageService->payload());
    }
}
