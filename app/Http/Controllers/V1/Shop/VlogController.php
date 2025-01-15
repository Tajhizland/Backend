<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Vlog\FindVlogByUrlRequest;
use App\Http\Resources\V1\Vlog\VlogCollection;
use App\Http\Resources\V1\Vlog\VlogResource;
use App\Services\Vlog\VlogServiceInterface;
use Illuminate\Http\Request;

class VlogController extends Controller
{
    public function __construct
    (
        private VlogServiceInterface $vlogService
    )
    {
    }

    public function find(FindVlogByUrlRequest $request)
    {
        $vlogResponse = $this->vlogService->findByUrl($request->get("url"));
        $relatedVlogs = $this->vlogService->getRelatedVlogs($vlogResponse->category_id, $vlogResponse->id);
        $this->vlogService->view($vlogResponse);
        return $this->dataResponse([
            "vlog" => new VlogResource($vlogResponse),
            "relatedVlogs" => new VlogCollection($relatedVlogs)
        ]);
    }

    public function listing(Request $request)
    {
        $listing = new VlogCollection($this->vlogService->listing($request->get("filter")));
        $mostViewed = new VlogCollection($this->vlogService->getMostViewed());
        return $this->dataResponse([
            "listing" => $listing,
            "mostViewed" => $mostViewed
        ]);
    }
}
