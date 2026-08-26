<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Cast\CastStoreDto;
use App\DTOs\Cast\CastUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cast\StoreCastRequest;
use App\Http\Requests\Admin\Cast\UpdateCastRequest;
use App\Http\Resources\Cast\CastResource;
use App\Services\Cast\CastServiceInterface;

class CastController extends Controller
{
    public function __construct
    (
        private readonly CastServiceInterface $castService
    )
    {
    }

    public function dataTable()
    {
        $response = $this->castService->dataTable();
        return $this->dataResponseCollection(CastResource::collection($response));
    }

    public function show($id)
    {
        $response = $this->castService->find($id);
        return $this->dataResponse(new CastResource($response));
    }

    public function store(StoreCastRequest $request)
    {
        $this->castService->store(new CastStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.cast")]));
    }

    public function update($id, UpdateCastRequest $request)
    {
        $this->castService->update(new CastUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.cast")]));
    }


}
