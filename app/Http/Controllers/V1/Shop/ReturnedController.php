<?php

namespace App\Http\Controllers\V1\Shop;

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
        $this->returnedService->store($request->get("order_id"), $request->get("order_item_id"), Auth::user()->id, $request->get("count"), $request->get("description"), $request->get("file"));
        return $this->successResponse(__("action.submit", ["attr" => __("attr.returned")]));
    }
}
