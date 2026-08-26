<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\CampaignSlider\CampaignSliderSortDto;
use App\DTOs\CampaignSlider\CampaignSliderStoreDto;
use App\DTOs\CampaignSlider\CampaignSliderUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CampaignSlider\SortCampaignSliderRequest;
use App\Http\Requests\Admin\CampaignSlider\StoreCampaignSliderRequest;
use App\Http\Requests\Admin\CampaignSlider\UpdateCampaignSliderRequest;
use App\Http\Resources\CampaignSlider\CampaignSliderResource;
use App\Services\CampaignSlider\CampaignSliderServiceInterface;

class CampaignSliderController extends Controller
{
    public function __construct
    (
        private readonly CampaignSliderServiceInterface $campaignSliderService
    )
    {
    }


    public function campaignDataTable($campaignId)
    {
        $response = $this->campaignSliderService->getByCampaignId($campaignId);
        return $this->dataResponseCollection(CampaignSliderResource::collection($response));
    }

    public function store(StoreCampaignSliderRequest $request)
    {
        $this->campaignSliderService->store(new CampaignSliderStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.filter")]));
    }

    public function update($id, UpdateCampaignSliderRequest $request)
    {
        $this->campaignSliderService->update(new CampaignSliderUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.filter")]));
    }

    public function getAllDesktop()
    {
        $response = $this->campaignSliderService->getAllDesktop();
        return $this->dataResponseCollection(CampaignSliderResource::collection($response));
    }

    public function getAllMobile()
    {
        $response = $this->campaignSliderService->getAllMobile();
        return $this->dataResponseCollection(CampaignSliderResource::collection($response));
    }

    public function sort(SortCampaignSliderRequest $request)
    {
        $this->campaignSliderService->sort(new CampaignSliderSortDto(...$request->validated()));
        return $this->successResponse(__("action.sort", ["attr" => __("attr.slider")]));
    }

    public function destroy($id)
    {
        $this->campaignSliderService->delete($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.slider")]));
    }

    public function show($id)
    {
        $response = $this->campaignSliderService->find($id);
        return $this->dataResponse(new CampaignSliderResource($response));
    }
}
