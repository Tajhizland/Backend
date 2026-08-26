<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrustedBrand\StoreTrustedBrandRequest;
use App\Http\Requests\TrustedBrand\UpdateTrustedBrandRequest;
use App\Http\Resources\TrustedBrand\TrustedBrandResource;
use App\Services\TrustedBrand\TrustedBrandServiceInterface;

class TrustedBrandController extends Controller
{
    public function __construct
    (
        private readonly TrustedBrandServiceInterface $trustedBrandService
    )
    {
    }

    public function dataTable()
    {
        $response = $this->trustedBrandService->dataTable();
        return $this->dataResponseCollection(TrustedBrandResource::collection($response));
    }

    public function store(StoreTrustedBrandRequest $request)
    {
        $this->trustedBrandService->store($request->get("logo"));
        return $this->successResponse(__("action.store", ["attr" => __("attr.image")]));

    }

    public function update(UpdateTrustedBrandRequest $request)
    {
        $this->trustedBrandService->update($request->get("id"), $request->get("logo"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.image")]));

    }

    public function find($id)
    {
        $response = $this->trustedBrandService->find($id);
        return $this->dataResponse(new TrustedBrandResource($response));
    }

    public function delete($id)
    {
        $this->trustedBrandService->delete($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.image")]));
    }
}
