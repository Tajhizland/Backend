<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Landing\FindByUrlRequest;
use App\Http\Resources\V1\Landing\LandingResource;
use App\Services\Landing\LandingServiceInterface;

class LandingController extends Controller
{
    public function __construct
    (
        private LandingServiceInterface $landingService
    )
    {
    }

    public function findByUrl(FindByUrlRequest $request)
    {
        return $this->dataResponse(new LandingResource($this->landingService->findByUrl($request->get("url"))));
    }
}
