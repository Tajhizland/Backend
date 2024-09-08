<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Returned\UpdateReturnedStatusRequest;
use App\Http\Resources\V1\Returned\ReturnedCollection;
use App\Services\Returned\ReturnedServiceInterface;
use Illuminate\Support\Facades\Lang;

class ReturnedController extends Controller
{
    public function __construct
    (
        private ReturnedServiceInterface $returnedService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponse(new ReturnedCollection($this->returnedService->dataTable()));
    }

    public function accept(UpdateReturnedStatusRequest $request)
    {
        $this->returnedService->accept($request->get("id"));
        return $this->successResponse(Lang::get("action.accept", ["attr" => Lang::get("attr.returned")]));
    }

    public function reject(UpdateReturnedStatusRequest $request)
    {
        $this->returnedService->reject($request->get("id"));
        return $this->successResponse(Lang::get("action.reject", ["attr" => Lang::get("attr.returned")]));
    }
}
