<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
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
        return $this->dataResponseCollection(ReturnedResource::collection($this->returnedService->dataTable()));
    }

    public function accept($id)
    {
        $this->returnedService->accept($id);
        return $this->successResponse(__("action.accept", ["attr" => __("attr.returned")]));
    }

    public function reject($id)
    {
        $this->returnedService->reject($id);
        return $this->successResponse(__("action.reject", ["attr" => __("attr.returned")]));
    }
}
