<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Banner\BannerSortRequest;
use App\Http\Requests\Admin\CampaignBanner\StoreCampaignBannerRequest;
use App\Http\Requests\Admin\CampaignBanner\UpdateCampaignBannerRequest;
use App\Http\Resources\CampaignBanner\CampaignBannerResource;
use App\Services\CampaignBanner\CampaignBannerServiceInterface;

class CampaignBannerController extends Controller
{
    public function __construct
    (
        private readonly CampaignBannerServiceInterface $campaignBannerService
    )
    {
    }

    public function dataTable($id)
    {
        return $this->dataResponseCollection(CampaignBannerResource::collection($this->campaignBannerService->dataTable($id)));
    }

    public function delete($id)
    {
        $this->campaignBannerService->delete($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.banner")]));
    }

    public function find($id)
    {
        return $this->dataResponse(new CampaignBannerResource($this->campaignBannerService->findById($id)));
    }

    public function store(StoreCampaignBannerRequest $request)
    {
        $this->campaignBannerService->create($request->file("image"), $request->get("url"), $request->get("type"), $request->get("campaign_id"));
        return $this->successResponse(__("action.store", ["attr" => __("attr.banner")]));
    }

    public function update(UpdateCampaignBannerRequest $request)
    {
        $this->campaignBannerService->update($request->get("id"), $request->file("image"), $request->get("url"), $request->get("type"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.banner")]));
    }

    public function list($type)
    {
        return $this->dataResponseCollection(CampaignBannerResource::collection($this->campaignBannerService->getByType($type)));
    }

    public function sort(BannerSortRequest $request)
    {
        $this->campaignBannerService->sort($request->get("banner"));
        return $this->successResponse(__("action.sort", ["attr" => __("attr.banner")]));
    }
}
