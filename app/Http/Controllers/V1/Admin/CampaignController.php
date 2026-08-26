<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Campaign\CampaignStoreDto;
use App\DTOs\Campaign\CampaignUpdateDto;
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

    public function show($id)
    {
        $response = $this->campaignService->find($id);
        return $this->dataResponse(new CampaignResource($response));
    }

    public function store(StoreCampaignRequest $request)
    {
        $this->campaignService->store(new CampaignStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.campaign")]));
    }

    public function update($id, UpdateCampaignRequest $request)
    {
        $this->campaignService->update(new CampaignUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.campaign")]));
    }
}
