<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Delivery\StoreDeliveryRequest;
use App\Http\Requests\V1\Admin\Delivery\UpdateDeliveryRequest;
use App\Http\Resources\V1\Delivery\DeliveryCollection;
use App\Http\Resources\V1\Delivery\DeliveryResource;
use App\Services\Delivery\DeliveryServiceInterface;
use Illuminate\Support\Facades\Lang;

class DeliveryController extends Controller
{
    public function __construct
    (
        private DeliveryServiceInterface $deliveryService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(new DeliveryCollection($this->deliveryService->dataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new DeliveryResource($this->deliveryService->findById($id)));
    }

    public function store(StoreDeliveryRequest $request)
    {
        $this->deliveryService->store($request->get("name"), $request->get("status"), $request->get("description"), $request->get("price"), $request->get("logo"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.delivery")]));
    }

    public function update(UpdateDeliveryRequest $request)
    {
        $this->deliveryService->update($request->get("id"), $request->get("name"), $request->get("status"), $request->get("description"), $request->get("price"), $request->get("logo"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.delivery")]));
    }
}
