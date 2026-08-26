<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HomepageVlog\UpdateHomePageVlogRequest;
use App\Services\HomepageVlog\HomepageVlogServiceInterface;
use App\Http\Resources\HomepageVlog\HomepageVlogResource;

class HomepageVlogController extends Controller
{
    public function __construct
    (
        private readonly HomepageVlogServiceInterface $homepageVlogService
    )
    {
    }

    public function get()
    {
        $response = $this->homepageVlogService->get();
        return $this->dataResponseCollection(HomepageVlogResource::collection($response));
    }

    public function update(UpdateHomePageVlogRequest $request)
    {
        $this->homepageVlogService->update($request->get("id"), $request->get("vlogId"));
        return $this->successResponse(__("action.update",["attr"=>__("attr.vlog")]));
    }
}
