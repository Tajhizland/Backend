<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Guaranty\GuarantyStoreDto;
use App\DTOs\Guaranty\GuarantyUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Guaranty\StoreGuarantyRequest;
use App\Http\Requests\Admin\Guaranty\UpdateGuarantyRequest;
use App\Http\Resources\Guaranty\GuarantyResource;
use App\Services\Guaranty\GuarantyServiceInterface;

class GuarantyController extends Controller
{
    public function __construct(
        private readonly GuarantyServiceInterface $guarantyService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(GuarantyResource::collection($this->guarantyService->dataTable()));
    }

    public function list()
    {
        return $this->dataResponseCollection(GuarantyResource::collection($this->guarantyService->getActives()));
    }

    public function show($id)
    {
        return $this->dataResponse(new GuarantyResource($this->guarantyService->find($id)));
    }

    public function store(StoreGuarantyRequest $request)
    {
        $this->guarantyService->store(new GuarantyStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.guaranty")]));
    }

    public function update($id, UpdateGuarantyRequest $request)
    {
        $this->guarantyService->update(new GuarantyUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.guaranty")]));
    }
}
