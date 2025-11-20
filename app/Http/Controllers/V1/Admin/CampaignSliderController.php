<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\CampaignSlider\StoreCampaignSliderRequest;
use App\Http\Requests\V1\Admin\CampaignSlider\UpdateCampaignSliderRequest;
use App\Http\Resources\V1\CampaignSlider\CampaignSliderCollection;
use App\Http\Resources\V1\CampaignSlider\CampaignSliderResource;
use App\Services\CampaignSlider\CampaignSliderServiceInterface;
use Illuminate\Support\Facades\Lang;

class CampaignSliderController extends Controller
{
    public function __construct
    (
        private CampaignSliderServiceInterface $campaignSliderService
    )
    {
    }


    public function campaignDataTable($campaignId)
    {
        $response = $this->campaignSliderService->getByCampaignId($campaignId);
        return $this->dataResponseCollection(new CampaignSliderCollection($response));
    }

    public function store(StoreCampaignSliderRequest $request)
    {
        $this->campaignSliderService->store($request->get("title"), $request->get("url"), $request->get("status"), $request->get("type"), $request->file("image"), $request->get("campaign_id"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.filter")]));
    }

    public function update(UpdateCampaignSliderRequest $request)
    {
        $this->campaignSliderService->update($request->get("id"), $request->get("title"), $request->get("url"), $request->get("status"), $request->get("type"), $request->file("image"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.filter")]));
    }

    public function getAllDesktop()
    {
        $response = $this->campaignSliderService->getAllDesktop();
        return $this->dataResponseCollection(new CampaignSliderCollection($response));
    }

    public function getAllMobile()
    {
        $response = $this->campaignSliderService->getAllMobile();
        return $this->dataResponseCollection(new CampaignSliderCollection($response));
    }

    public function sort(StoreCampaignSliderRequest $request)
    {
        $this->campaignSliderService->sort($request->get("slider"));
        return $this->successResponse(Lang::get("action.sort", ["attr" => Lang::get("attr.slider")]));
    }

    public function delete($id)
    {
        $this->campaignSliderService->delete($id);
        return $this->successResponse(Lang::get("action.remove", ["attr" => Lang::get("attr.slider")]));
    }

    public function find($id)
    {
        $response = $this->campaignSliderService->find($id);
        return $this->dataResponse(new CampaignSliderResource($response));
    }
}
