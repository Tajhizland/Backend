<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\PhoneBock\StorePhoneBockRequest;
use App\Http\Requests\V1\Admin\PhoneBock\UpdatePhoneBockRequest;
use App\Http\Resources\V1\PhoneBock\PhoneBockCollection;
use App\Http\Resources\V1\PhoneBock\PhoneBockResource;
use App\Services\PhoneBock\PhoneBockService;
use Illuminate\Support\Facades\Lang;

class PhoneBockController extends Controller
{
    public function __construct(
        private PhoneBockService $phoneBockService
    )
    {
    }

    public function dataTable()
    {
        $response = $this->phoneBockService->dataTable();
        return $this->dataResponseCollection(PhoneBockCollection::make($response));
    }

    public function all()
    {
        $response = $this->phoneBockService->getAll();
        return $this->dataResponseCollection(PhoneBockCollection::make($response));
    }

    public function find($id)
    {
        $response = $this->phoneBockService->find($id);
        return $this->dataResponse(PhoneBockResource::make($response));
    }

    public function store(StorePhoneBockRequest $request)
    {
        $this->phoneBockService->store($request->get("name"), $request->get("mobile"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.contact")]));
    }

    public function update(UpdatePhoneBockRequest $request)
    {
        $this->phoneBockService->update($request->get("id"), $request->get("name"), $request->get("mobile"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.contact")]));
    }
}
