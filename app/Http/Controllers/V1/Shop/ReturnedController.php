<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Returned\StoreReturnedRequest;
use App\Services\Returned\ReturnedServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class ReturnedController extends Controller
{
    public function __construct(
        private ReturnedServiceInterface $returnedService
    )
    {
    }

    public function store(StoreReturnedRequest $request)
    {
        $this->returnedService->store($request->get("order_id"), $request->get("order_item_id"), Auth::user()->id, $request->get("count"), $request->get("description"), $request->get("file"));
        return $this->successResponse(Lang::get("action.submit", ["attr" => Lang::get("attr.returned")]));
    }
}
