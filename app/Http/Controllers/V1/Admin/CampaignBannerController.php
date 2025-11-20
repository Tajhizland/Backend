<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Banner\BannerSortRequest;
use App\Http\Requests\V1\Admin\CampaignBanner\StoreCampaignBannerRequest;
use App\Http\Requests\V1\Admin\CampaignBanner\UpdateCampaignBannerRequest;
use App\Http\Resources\V1\CampaignBanner\CampaignBannerCollection;
use App\Http\Resources\V1\CampaignBanner\CampaignBannerResource;
use App\Services\CampaignBanner\CampaignBannerServiceInterface;
use Illuminate\Support\Facades\Lang;

class CampaignBannerController extends Controller
{
    public function __construct
    (
        private CampaignBannerServiceInterface $campaignBannerService
    )
    {
    }

    public function dataTable($id)
    {
        return $this->dataResponseCollection(new CampaignBannerCollection($this->campaignBannerService->dataTable($id)));
    }

    public function delete($id)
    {
        $this->campaignBannerService->delete($id);
        return $this->successResponse(Lang::get("action.remove", ["attr" => Lang::get("attr.banner")]));
    }

    public function find($id)
    {
        return $this->dataResponse(new CampaignBannerResource($this->campaignBannerService->findById($id)));
    }

    public function store(StoreCampaignBannerRequest $request)
    {
        $this->campaignBannerService->create($request->file("image"), $request->get("url"), $request->get("type"), $request->get("campaign_id"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.banner")]));
    }

    public function update(UpdateCampaignBannerRequest $request)
    {
        $this->campaignBannerService->update($request->get("id"), $request->file("image"), $request->get("url"), $request->get("type"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.banner")]));
    }

    public function list($type)
    {
        return $this->dataResponseCollection(new CampaignBannerCollection($this->campaignBannerService->getByType($type)));
    }

    public function sort(BannerSortRequest $request)
    {
        $this->campaignBannerService->sort($request->get("banner"));
        return $this->successResponse(Lang::get("action.sort", ["attr" => Lang::get("attr.banner")]));
    }
}
