<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Gateway\GatewayStoreDto;
use App\DTOs\Gateway\GatewayUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Gateway\StoreGatewayRequest;
use App\Http\Requests\Admin\Gateway\UpdateGatewayRequest;
use App\Http\Resources\Gateway\GatewayResource;
use App\Services\Gateway\GatewayServiceInterface;

class GatewayController extends Controller
{
    public function __construct(
        private readonly GatewayServiceInterface $gatewayService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(GatewayResource::collection($this->gatewayService->dataTable()));
    }

    public function show($id)
    {
        return $this->dataResponse(new GatewayResource($this->gatewayService->find($id)));
    }

    public function store(StoreGatewayRequest $request)
    {
        $this->gatewayService->store(new GatewayStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.gateway")]));
    }

    public function update($id, UpdateGatewayRequest $request)
    {
        $this->gatewayService->update(new GatewayUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.gateway")]));
    }
}
