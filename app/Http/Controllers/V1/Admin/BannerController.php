<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Banner\BannerSortRequest;
use App\Http\Requests\Admin\Banner\StoreBannerRequest;
use App\Http\Requests\Admin\Banner\UpdateBannerRequest;
use App\Http\Resources\Banner\BannerResource;
use App\Services\Banner\BannerServiceInterface;

class BannerController extends Controller
{
    public function __construct
    (
        private readonly BannerServiceInterface $bannerService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(BannerResource::collection($this->bannerService->dataTable()));
    }

    public function delete($id)
    {
        $this->bannerService->delete($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.banner")]));
    }

    public function find($id)
    {
        return $this->dataResponse(new BannerResource($this->bannerService->findById($id)));
    }

    public function store(StoreBannerRequest $request)
    {
        $this->bannerService->create($request->file("image"), $request->get("url"),$request->get("type"));
        return $this->successResponse(__("action.store", ["attr" => __("attr.banner")]));
    }

    public function update(UpdateBannerRequest $request)
    {
        $this->bannerService->update($request->get("id"), $request->file("image"), $request->get("url"),$request->get("type"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.banner")]));
    }
    public function list()
    {
        return $this->dataResponseCollection(BannerResource::collection($this->bannerService->getAll()));
    }
    public function sort(BannerSortRequest $request)
    {
        $this->bannerService->sort($request->get("banner"));
        return $this->successResponse(__("action.sort", ["attr" => __("attr.banner")]));
    }
}
