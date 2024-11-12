<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Banner\StoreBannerRequest;
use App\Http\Requests\V1\Admin\Banner\UpdateBannerRequest;
use App\Http\Resources\V1\Banner\BannerCollection;
use App\Services\Banner\BannerServiceInterface;
use Illuminate\Support\Facades\Lang;

class BannerController extends Controller
{
    public function __construct
    (
        private BannerServiceInterface $bannerService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(new BannerCollection($this->bannerService->dataTable()));
    }

    public function delete($id)
    {
        $this->bannerService->delete($id);
        return $this->successResponse(Lang::get("action.remove", ["attr" => Lang::get("attr.banner")]));
    }

    public function create(StoreBannerRequest $request)
    {
        $this->bannerService->create($request->get("image"), $request->get("url"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.banner")]));
    }

    public function update(UpdateBannerRequest $request)
    {
        $this->bannerService->update($request->get("id"), $request->get("image"), $request->get("url"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.banner")]));
    }
}
