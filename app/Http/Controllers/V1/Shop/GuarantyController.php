<?php

namespace App\Http\Controllers\V1\Shop;

use App\DTOs\Guaranty\GuarantyFindDto;
use App\Http\Requests\Shop\Guaranty\FindGuarantyRequest;
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

    public function findByUrl(FindGuarantyRequest $request)
    {
        return $this->dataResponse(new GuarantyResource($this->guarantyService->findByUrl((new GuarantyFindDto(...$request->validated()))->url)));
    }
}
