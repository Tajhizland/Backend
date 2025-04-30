<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\HomepageVlog\UpdateHomePageVlogRequest;
use App\Http\Resources\V1\HomepageVlog\HomePageVlogCollection;
use App\Services\HomepageVlog\HomepageVlogServiceInterface;
use Illuminate\Support\Facades\Lang;

class HomepageVlogController extends Controller
{
    public function __construct
    (
        private HomepageVlogServiceInterface $homepageVlogService
    )
    {
    }

    public function get()
    {
        $response = $this->homepageVlogService->get();
        return $this->dataResponseCollection(new HomePageVlogCollection($response));
    }

    public function update(UpdateHomePageVlogRequest $request)
    {
        $this->homepageVlogService->update($request->get("id"), $request->get("vlogId"));
        return $this->successResponse(Lang::get("action.update",["attr"=>Lang::get("attr.vlog")]));
    }
}
