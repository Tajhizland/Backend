<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\TrustedBrand\StoreTrustedBrandRequest;
use App\Http\Requests\V1\TrustedBrand\UpdateTrustedBrandRequest;
use App\Http\Resources\V1\TrustedBrand\TrustedBrandCollection;
use App\Http\Resources\V1\TrustedBrand\TrustedBrandResource;
use App\Services\TrustedBrand\TrustedBrandServiceInterface;
use Illuminate\Support\Facades\Lang;

class TrustedBrandController extends Controller
{
    public function __construct
    (
        private TrustedBrandServiceInterface $trustedBrandService
    )
    {
    }

    public function dataTable()
    {
        $response = $this->trustedBrandService->dataTable();
        return $this->dataResponseCollection(new TrustedBrandCollection($response));
    }

    public function store(StoreTrustedBrandRequest $request)
    {
        $this->trustedBrandService->store($request->get("logo"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.image")]));

    }

    public function update(UpdateTrustedBrandRequest $request)
    {
        $this->trustedBrandService->update($request->get("id"), $request->get("logo"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.image")]));

    }

    public function find($id)
    {
        $response = $this->trustedBrandService->find($id);
        return $this->dataResponse(new TrustedBrandResource($response));
    }

    public function delete($id)
    {
        $this->trustedBrandService->delete($id);
        return $this->successResponse(Lang::get("action.remove", ["attr" => Lang::get("attr.image")]));
    }
}
