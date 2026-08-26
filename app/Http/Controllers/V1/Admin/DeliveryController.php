<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Delivery\StoreDeliveryRequest;
use App\Http\Requests\Admin\Delivery\UpdateDeliveryRequest;
use App\Http\Resources\Delivery\DeliveryResource;
use App\Services\Delivery\DeliveryServiceInterface;

class DeliveryController extends Controller
{
    public function __construct
    (
        private readonly DeliveryServiceInterface $deliveryService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(DeliveryResource::collection($this->deliveryService->dataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new DeliveryResource($this->deliveryService->findById($id)));
    }

    public function store(StoreDeliveryRequest $request)
    {
        $this->deliveryService->store($request->get("name"), $request->get("status"), $request->get("description"), $request->get("price"), $request->get("logo"));
        return $this->successResponse(__("action.store", ["attr" => __("attr.delivery")]));
    }

    public function update(UpdateDeliveryRequest $request)
    {
        $this->deliveryService->update($request->get("id"), $request->get("name"), $request->get("status"), $request->get("description"), $request->get("price"), $request->get("logo"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.delivery")]));
    }
}
