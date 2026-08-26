<?php

namespace App\Http\Controllers\V1\Shop;

use App\DTOs\Returned\ReturnedStoreDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\Returned\StoreReturnedRequest;
use App\Services\Returned\ReturnedServiceInterface;
use Illuminate\Support\Facades\Auth;

class ReturnedController extends Controller
{
    public function __construct(
        private readonly ReturnedServiceInterface $returnedService
    )
    {
    }

    public function store(StoreReturnedRequest $request)
    {
        $this->returnedService->store(new ReturnedStoreDto(Auth::user()->id, ...$request->validated()));
        return $this->successResponse(__("action.submit", ["attr" => __("attr.returned")]));
    }
}
