<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Banner\BannerSortDto;
use App\DTOs\Banner\BannerStoreDto;
use App\DTOs\Banner\BannerUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Banner\BannerSortRequest;
use App\Http\Requests\Admin\Banner\StoreBannerRequest;
use App\Http\Requests\Admin\Banner\UpdateBannerRequest;
use App\Http\Resources\Banner\BannerResource;
use App\Services\Banner\BannerServiceInterface;

class BannerController extends Controller
{
    public function __construct(
        private readonly BannerServiceInterface $bannerService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(BannerResource::collection($this->bannerService->dataTable()));
    }

    public function list()
    {
        return $this->dataResponseCollection(BannerResource::collection($this->bannerService->getAll()));
    }

    public function show($id)
    {
        return $this->dataResponse(new BannerResource($this->bannerService->find($id)));
    }

    public function store(StoreBannerRequest $request)
    {
        $this->bannerService->store(new BannerStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.banner")]));
    }

    public function update($id, UpdateBannerRequest $request)
    {
        $this->bannerService->update(new BannerUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.banner")]));
    }

    public function destroy($id)
    {
        $this->bannerService->delete($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.banner")]));
    }

    public function sort(BannerSortRequest $request)
    {
        $this->bannerService->sort(new BannerSortDto(...$request->validated()));
        return $this->successResponse(__("action.sort", ["attr" => __("attr.banner")]));
    }
}
