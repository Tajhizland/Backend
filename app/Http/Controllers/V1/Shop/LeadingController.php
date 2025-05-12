<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeadingResource;
use App\Services\Leading\LeadingServiceInterface;

class LeadingController extends Controller
{
    public function __construct
    (
        private LeadingServiceInterface $leadingService
    )
    {
    }

    public function index()
    {
        $response = $this->leadingService->index();
        return $this->dataResponse(new LeadingResource($response));
    }
}
