<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\CampaignBanner\CampaignBannerSortDto;
use App\DTOs\CampaignBanner\CampaignBannerStoreDto;
use App\DTOs\CampaignBanner\CampaignBannerUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CampaignBanner\SortCampaignBannerRequest;
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

    public function destroy($id)
    {
        $this->campaignBannerService->delete($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.banner")]));
    }

    public function show($id)
    {
        return $this->dataResponse(new CampaignBannerResource($this->campaignBannerService->find($id)));
    }

    public function store(StoreCampaignBannerRequest $request)
    {
        $this->campaignBannerService->store(new CampaignBannerStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.banner")]));
    }

    public function update($id, UpdateCampaignBannerRequest $request)
    {
        $this->campaignBannerService->update(new CampaignBannerUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.banner")]));
    }

    public function list($type)
    {
        return $this->dataResponseCollection(CampaignBannerResource::collection($this->campaignBannerService->getByType($type)));
    }

    public function sort(SortCampaignBannerRequest $request)
    {
        $this->campaignBannerService->sort(new CampaignBannerSortDto(...$request->validated()));
        return $this->successResponse(__("action.sort", ["attr" => __("attr.banner")]));
    }
}
