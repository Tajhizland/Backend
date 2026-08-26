<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Gateway\StoreGatewayRequest;
use App\Http\Requests\Admin\Gateway\UpdateGatewayRequest;
use App\Http\Resources\Gateway\GatewayResource;
use App\Services\Gateway\GatewayServiceInterface;

class GatewayController extends Controller
{
    public function __construct
    (
        private readonly GatewayServiceInterface $gatewayService
    ) { }

    public function dataTable()
    {
        return $this->dataResponseCollection(GatewayResource::collection($this->gatewayService->dataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new GatewayResource($this->gatewayService->findById($id)));
    }

    public function store(StoreGatewayRequest $request)
    {
        $this->gatewayService->store($request->get("name"), $request->get("status"), $request->get("description"));
        return $this->successResponse(__("action.store",["attr"=>__("attr.gateway")]));
    }

    public function update(UpdateGatewayRequest $request)
    {
        $this->gatewayService->update($request->get("id"), $request->get("name"), $request->get("status"), $request->get("description"));
        return $this->successResponse(__("action.update",["attr"=>__("attr.gateway")]));
    }
}
