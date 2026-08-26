<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Guaranty\GuarantyResource;
use App\Services\Guaranty\GuarantyServiceInterface;
use Illuminate\Http\Request;

class GuarantyController extends Controller
{
    public function __construct
    (
        private readonly GuarantyServiceInterface $guarantyService
    )
    {
    }

    public function findByUrl(Request $request)
    {
        return $this->dataResponse(new GuarantyResource($this->guarantyService->findByUrl($request->get("url"))));
    }
}
