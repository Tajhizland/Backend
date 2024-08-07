<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Gateway\StoreGatewayRequest;
use App\Http\Requests\V1\Admin\Gateway\UpdateGatewayRequest;
use App\Http\Resources\V1\Gateway\GatewayCollection;
use App\Http\Resources\V1\Gateway\GatewayResource;
use App\Services\Gateway\GatewayServiceInterface;
use Illuminate\Support\Facades\Lang;

class GatewayController extends Controller
{
    public function __construct
    (
        private GatewayServiceInterface $gatewayService
    ) { }

    public function dataTable()
    {
        return $this->dataResponse(new GatewayCollection($this->gatewayService->dataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new GatewayResource($this->gatewayService->findById($id)));
    }

    public function store(StoreGatewayRequest $request)
    {
        $this->gatewayService->store($request->get("name"), $request->get("status"), $request->get("description"));
        return $this->successResponse(Lang::get("action.store",["attr"=>Lang::get("attr.gateway")]));
    }

    public function update(UpdateGatewayRequest $request)
    {
        $this->gatewayService->update($request->get("id"), $request->get("name"), $request->get("status"), $request->get("description"));
        return $this->successResponse(Lang::get("action.update",["attr"=>Lang::get("attr.gateway")]));
    }
}
