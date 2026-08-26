<?php

namespace App\Http\Controllers\V1\Admin;

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

    public function find($id)
    {
        $response = $this->castService->find($id);
        return $this->dataResponse(new CastResource($response));
    }

    public function store(StoreCastRequest $request)
    {
        $this->castService->store($request->get("title"), $request->file("image"), $request->get("description"), $request->get("url"), $request->get("status"), $request->file("audio"), $request->get("vlog_id"), $request->get("category_id"));
        return $this->successResponse(__("action.store", ["attr" => __("attr.cast")]));
    }

    public function update(UpdateCastRequest $request)
    {
        $this->castService->update($request->get("id"), $request->get("title"), $request->file("image"), $request->get("description"), $request->get("url"), $request->get("status"), $request->file("audio"), $request->get("vlog_id"), $request->get("category_id"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.cast")]));
    }


}
