<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Poster\StorePosterRequest;
use App\Http\Requests\Admin\Poster\UpdatePosterRequest;
use App\Http\Resources\Poster\PosterResource;
use App\Services\Poster\PosterServiceInterface;

class PosterController extends Controller
{
    public function __construct
    (
        private readonly PosterServiceInterface $posterService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(PosterResource::collection($this->posterService->dataTable()));
    }

    public function store(StorePosterRequest $request)
    {
        $this->posterService->store($request->file("image"));
        return $this->successResponse(__("action.store", ["attr" => __("attr.poster")]));
    }

    public function find($id)
    {
        return $this->dataResponse(new PosterResource($this->posterService->findById($id)));
    }

    public function update(UpdatePosterRequest $request)
    {
        $this->posterService->update($request->get("id"),$request->file("image"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.poster")]));
    }
}
