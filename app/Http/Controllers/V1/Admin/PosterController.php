<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Poster\StorePosterRequest;
use App\Http\Requests\V1\Admin\Poster\UpdatePosterRequest;
use App\Http\Resources\V1\Poster\PosterCollection;
use App\Http\Resources\V1\Poster\PosterResource;
use App\Services\Poster\PosterServiceInterface;
use Illuminate\Support\Facades\Lang;

class PosterController extends Controller
{
    public function __construct
    (
        private PosterServiceInterface $posterService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(new PosterCollection($this->posterService->dataTable()));
    }

    public function store(StorePosterRequest $request)
    {
        $this->posterService->store($request->file("image"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.poster")]));
    }

    public function find($id)
    {
        return $this->dataResponse(new PosterResource($this->posterService->findById($id)));
    }

    public function update(UpdatePosterRequest $request)
    {
        $this->posterService->update($request->get("id"),$request->file("image"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.poster")]));
    }
}
