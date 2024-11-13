<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Vlog\FindVlogByUrlRequest;
use App\Http\Resources\V1\Vlog\VlogCollection;
use App\Http\Resources\V1\Vlog\VlogResource;
use App\Services\Vlog\VlogServiceInterface;

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
        return $this->dataResponse(new VlogResource($this->vlogService->findByUrl($request->get("url"))));
    }

    public function listing()
    {
        return $this->dataResponseCollection(new VlogCollection($this->vlogService->listing()));
    }
}
