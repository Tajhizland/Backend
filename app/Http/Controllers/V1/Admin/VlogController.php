<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Vlog\StoreVlogRequest;
use App\Http\Requests\V1\Admin\Vlog\UpdateVlogRequest;
use App\Http\Requests\V1\Admin\Vlog\VlogSearchRequest;
use App\Http\Resources\V1\Vlog\VlogCollection;
use App\Http\Resources\V1\Vlog\VlogResource;
use App\Services\Vlog\VlogServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class VlogController extends Controller
{
    public function __construct
    (
        private VlogServiceInterface $vlogService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(new VlogCollection($this->vlogService->dataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new VlogResource($this->vlogService->findById($id)));
    }

    public function store(StoreVlogRequest $request)
    {
        $userId = Auth::user()->id;
        $this->vlogService->store($request->get("title"), $request->get("description"), $request->file("video"), $request->file("poster"), $request->get("url"), $request->get("status"), $request->get("categoryId") ,$userId);
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.vlog")]));
    }

    public function update(UpdateVlogRequest $request)
    {
        $this->vlogService->update($request->get("id"), $request->get("title"), $request->get("description"), $request->file("video"), $request->file("poster"), $request->get("url"), $request->get("status"), $request->get("categoryId"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.vlog")]));
    }
    public function search(VlogSearchRequest $request)
    {
        return $this->dataResponseCollection(new VlogCollection($this->vlogService->search($request->get("query"))));
    }
}
