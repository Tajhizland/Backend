<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Delivery\DeliveryStoreDto;
use App\DTOs\Delivery\DeliveryUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Delivery\StoreDeliveryRequest;
use App\Http\Requests\Admin\Delivery\UpdateDeliveryRequest;
use App\Http\Resources\Delivery\DeliveryResource;
use App\Services\Delivery\DeliveryServiceInterface;

class DeliveryController extends Controller
{
    public function __construct(
        private readonly DeliveryServiceInterface $deliveryService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(DeliveryResource::collection($this->deliveryService->dataTable()));
    }

    public function show($id)
    {
        return $this->dataResponse(new DeliveryResource($this->deliveryService->find($id)));
    }

    public function store(StoreDeliveryRequest $request)
    {
        $this->deliveryService->store(new DeliveryStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.delivery")]));
    }

    public function update($id, UpdateDeliveryRequest $request)
    {
        $this->deliveryService->update(new DeliveryUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.delivery")]));
    }
}
