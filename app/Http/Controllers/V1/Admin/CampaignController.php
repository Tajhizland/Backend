<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Campaign\StoreCampaignRequest;
use App\Http\Requests\Admin\Campaign\UpdateCampaignRequest;
use App\Http\Resources\Campaign\CampaignResource;
use App\Services\Campaign\CampaignServiceInterface;

class CampaignController extends Controller
{
    public function __construct
    (
        private readonly CampaignServiceInterface $campaignService
    )
    {
    }

    public function dataTable()
    {
        $response = $this->campaignService->dataTable();
        return $this->dataResponseCollection(CampaignResource::collection($response));
    }

    public function find($id)
    {
        $response = $this->campaignService->find($id);
        return $this->dataResponse(new CampaignResource($response));
    }

    public function store(StoreCampaignRequest $request)
    {
        $this->campaignService->store(
            $request->get("title"),
            $request->get("status"),
            $request->get("color"),
            $request->get("start_date"),
            $request->get("end_date"),
            $request->file("logo"),
            $request->file("banner"),
            $request->get("background_color"),
            $request->file("discount_logo"),
        );
        return $this->successResponse(__("action.store", ["attr" => __("attr.campaign")]));
    }

    public function update(UpdateCampaignRequest $request)
    {
        $this->campaignService->update(
            $request->get("id"),
            $request->get("title"),
            $request->get("status"),
            $request->get("color"),
            $request->get("start_date"),
            $request->get("end_date"),
            $request->file("logo"),
            $request->file("banner"),
            $request->get("background_color"),
            $request->file("discount_logo"),
        );
        return $this->successResponse(__("action.update", ["attr" => __("attr.campaign")]));
    }
}
