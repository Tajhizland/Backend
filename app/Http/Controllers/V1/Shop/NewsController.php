<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\News\NewsCollection;
use App\Http\Resources\V1\News\NewsResource;
use App\Services\New\NewServiceInterface;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function __construct
    (
        private NewServiceInterface $newService
    )
    {
    }

    public function paginate()
    {
        return $this->dataResponse(new NewsCollection($this->newService->activePaginate()));
    }

    public function findByUrl(Request $request)
    {
        return $this->dataResponse(new NewsResource($this->newService->findByUrl($request->url)));
    }
}
