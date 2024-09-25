<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\HomePage\HomePageResource;
use App\Services\HomePage\HomePageServiceInterface;

class HomePageController extends Controller
{
    public function __construct
    (
        private HomePageServiceInterface $homePageService
    )
    {
    }

    public function index()
    {
        return $this->dataResponse(new HomePageResource($this->homePageService->buildData()));
    }
}
