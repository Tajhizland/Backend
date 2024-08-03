<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\News\NewsCollection;
use App\Http\Resources\V1\News\NewsResource;
use App\Services\New\NewServiceInterface;

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

    public function findByUrl($url)
    {
        return $this->dataResponse(new NewsResource($this->newService->findByUrl($url)));
    }
}
