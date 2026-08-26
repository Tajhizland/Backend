<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Vlog\StoreVlogDirectRequest;
use App\Http\Requests\Admin\Vlog\StoreVlogRequest;
use App\Http\Requests\Admin\Vlog\UpdateVlogRequest;
use App\Http\Requests\Admin\Vlog\VlogSearchRequest;
use App\Http\Requests\Admin\Vlog\VlogSortRequest;
use App\Http\Resources\Vlog\VlogResource;
use App\Services\Vlog\VlogServiceInterface;
use Illuminate\Support\Facades\Auth;

class VlogController extends Controller
{
    public function __construct
    (
        private readonly VlogServiceInterface $vlogService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(VlogResource::collection($this->vlogService->dataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new VlogResource($this->vlogService->findById($id)));
    }

    public function store(StoreVlogRequest $request)
    {
        $userId = Auth::user()->id;
        $this->vlogService->store($request->get("title"), $request->get("description"), $request->file("video"), $request->file("poster"), $request->get("url"), $request->get("status"), $request->get("categoryId") ,$userId);
        return $this->successResponse(__("action.store", ["attr" => __("attr.vlog")]));
    }

    /** ثبت ولاگ با ویدیویی که مستقیماً روی S3 آپلود شده است */
    public function storeDirect(StoreVlogDirectRequest $request)
    {
        $userId = Auth::user()->id;

        $vlog = $this->vlogService->storeDirect(
            $request->get("title"),
            $request->get("description"),
            $request->get("videoKey"),
            $request->file("poster"),
            $request->get("url"),
            $request->get("status"),
            $request->get("categoryId"),
            $userId
        );

        return $this->dataResponse(
            new VlogResource($vlog),
            __("action.store", ["attr" => __("attr.vlog")])
        );
    }

    /** وضعیت پردازش ویدیو؛ فرانت بعد از پایان آپلود این را poll می‌کند */
    public function videoStatus($id)
    {
        $vlog = $this->vlogService->findById($id);

        return $this->dataResponse([
            'id' => $vlog->id,
            'videoStatus' => $vlog->video_status,
            'videoError' => $vlog->video_error,
            'hls' => $vlog->hls,
        ]);
    }

    public function update(UpdateVlogRequest $request)
    {
        $this->vlogService->update($request->get("id"), $request->get("title"), $request->get("description"), $request->file("video"), $request->file("poster"), $request->get("url"), $request->get("status"), $request->get("categoryId"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.vlog")]));
    }
    public function search(VlogSearchRequest $request)
    {
        return $this->dataResponseCollection(VlogResource::collection($this->vlogService->search($request->get("query"))));
    }

    public function list()
    {
        return $this->dataResponseCollection(VlogResource::collection($this->vlogService->list()));
    }

    public function sort(VlogSortRequest $request)
    {
        $this->vlogService->sort($request->get("vlog"));
        return $this->successResponse(__("action.sort", ["attr" => __("attr.vlog")]));
    }

}
