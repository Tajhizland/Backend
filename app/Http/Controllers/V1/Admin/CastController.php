<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Cast\StoreCastRequest;
use App\Http\Requests\V1\Admin\Cast\UpdateCastRequest;
use App\Http\Resources\CastResource;
use App\Http\Resources\V1\Cast\CastCollection;
use App\Services\Cast\CastServiceInterface;
use Illuminate\Support\Facades\Lang;

class CastController extends Controller
{
    public function __construct
    (
        private CastServiceInterface $castService
    )
    {
    }

    public function dataTable()
    {
        $response = $this->castService->dataTable();
        return $this->dataResponseCollection(new CastCollection($response));
    }

    public function find($id)
    {
        $response = $this->castService->find($id);
        return $this->dataResponse(new CastResource($response));
    }

    public function store(StoreCastRequest $request)
    {
        $this->castService->store($request->get("title"), $request->file("image"), $request->get("description"), $request->get("url"), $request->get("status"), $request->file("audio"), $request->get("vlog_id"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.cast")]));
    }

    public function update(UpdateCastRequest $request)
    {
        $this->castService->update($request->get("id"), $request->get("title"), $request->file("image"), $request->get("description"), $request->get("url"), $request->get("status"), $request->file("audio"), $request->get("vlog_id"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.cast")]));
    }


}
