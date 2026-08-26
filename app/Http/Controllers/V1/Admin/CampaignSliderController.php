<?php

namespace App\Http\Controllers\V1\Admin;

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
        $this->campaignSliderService->store($request->get("title"), $request->get("url"), $request->get("status"), $request->get("type"), $request->file("image"), $request->get("campaign_id"));
        return $this->successResponse(__("action.store", ["attr" => __("attr.filter")]));
    }

    public function update(UpdateCampaignSliderRequest $request)
    {
        $this->campaignSliderService->update($request->get("id"), $request->get("title"), $request->get("url"), $request->get("status"), $request->get("type"), $request->file("image"));
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
        $this->campaignSliderService->sort($request->get("slider"));
        return $this->successResponse(__("action.sort", ["attr" => __("attr.slider")]));
    }

    public function delete($id)
    {
        $this->campaignSliderService->delete($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.slider")]));
    }

    public function find($id)
    {
        $response = $this->campaignSliderService->find($id);
        return $this->dataResponse(new CampaignSliderResource($response));
    }
}
