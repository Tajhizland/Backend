<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Poster\PosterStoreDto;
use App\DTOs\Poster\PosterUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Poster\StorePosterRequest;
use App\Http\Requests\Admin\Poster\UpdatePosterRequest;
use App\Http\Resources\Poster\PosterResource;
use App\Services\Poster\PosterServiceInterface;

class PosterController extends Controller
{
    public function __construct(
        private readonly PosterServiceInterface $posterService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(PosterResource::collection($this->posterService->dataTable()));
    }

    public function show($id)
    {
        return $this->dataResponse(new PosterResource($this->posterService->find($id)));
    }

    public function store(StorePosterRequest $request)
    {
        $this->posterService->store(new PosterStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.poster")]));
    }

    public function update($id, UpdatePosterRequest $request)
    {
        $this->posterService->update(new PosterUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.poster")]));
    }
}
