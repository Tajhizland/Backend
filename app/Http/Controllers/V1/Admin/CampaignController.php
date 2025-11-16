<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Campaign\StoreCampaignRequest;
use App\Http\Requests\V1\Admin\Campaign\UpdateCampaignRequest;
use App\Http\Resources\V1\Campaign\CampaignCollection;
use App\Http\Resources\V1\Campaign\CampaignResource;
use App\Services\Campaign\CampaignServiceInterface;
use Illuminate\Support\Facades\Lang;

class CampaignController extends Controller
{
    public function __construct
    (
        private CampaignServiceInterface $campaignService
    )
    {
    }

    public function dataTable()
    {
        $response = $this->campaignService->dataTable();
        return $this->dataResponseCollection(new CampaignCollection($response));
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
        );
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.campaign")]));
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
        );
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.campaign")]));
    }
}
