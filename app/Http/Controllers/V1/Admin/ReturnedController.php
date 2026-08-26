<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Returned\UpdateReturnedStatusRequest;
use App\Services\Returned\ReturnedServiceInterface;
use App\Http\Resources\Returned\ReturnedResource;

class ReturnedController extends Controller
{
    public function __construct
    (
        private readonly ReturnedServiceInterface $returnedService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponse(ReturnedResource::collection($this->returnedService->dataTable())->response()->getData());
    }

    public function accept(UpdateReturnedStatusRequest $request)
    {
        $this->returnedService->accept($request->get("id"));
        return $this->successResponse(__("action.accept", ["attr" => __("attr.returned")]));
    }

    public function reject(UpdateReturnedStatusRequest $request)
    {
        $this->returnedService->reject($request->get("id"));
        return $this->successResponse(__("action.reject", ["attr" => __("attr.returned")]));
    }
}
