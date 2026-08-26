<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Vlog\VlogSortDto;
use App\DTOs\Vlog\VlogStoreDirectDto;
use App\DTOs\Vlog\VlogStoreDto;
use App\DTOs\Vlog\VlogUpdateDto;
use App\DTOs\Vlog\VlogSearchDto;
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

    public function show($id)
    {
        return $this->dataResponse(new VlogResource($this->vlogService->find($id)));
    }

    public function store(StoreVlogRequest $request)
    {
        $this->vlogService->store(new VlogStoreDto(Auth::user()->id, ...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.vlog")]));
    }

    /** ثبت ولاگ با ویدیویی که مستقیماً روی S3 آپلود شده است */
    public function storeDirect(StoreVlogDirectRequest $request)
    {
        $userId = Auth::user()->id;

        $vlog = $this->vlogService->storeDirect(new VlogStoreDirectDto($userId, ...$request->validated()));

        return $this->dataResponse(
            new VlogResource($vlog),
            __("action.store", ["attr" => __("attr.vlog")])
        );
    }

    /** وضعیت پردازش ویدیو؛ فرانت بعد از پایان آپلود این را poll می‌کند */
    public function videoStatus($id)
    {
        $vlog = $this->vlogService->find($id);

        return $this->dataResponse([
            'id' => $vlog->id,
            'videoStatus' => $vlog->video_status,
            'videoError' => $vlog->video_error,
            'hls' => $vlog->hls,
        ]);
    }

    public function update($id, UpdateVlogRequest $request)
    {
        $this->vlogService->update(new VlogUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.vlog")]));
    }
    public function search(VlogSearchRequest $request)
    {
        return $this->dataResponseCollection(VlogResource::collection($this->vlogService->search((new VlogSearchDto(...$request->validated()))->query)));
    }

    public function list()
    {
        return $this->dataResponseCollection(VlogResource::collection($this->vlogService->list()));
    }

    public function sort(VlogSortRequest $request)
    {
        $this->vlogService->sort(new VlogSortDto(...$request->validated()));
        return $this->successResponse(__("action.sort", ["attr" => __("attr.vlog")]));
    }

}
