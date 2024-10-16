<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Page\FindPageRequest;
use App\Http\Resources\V1\Page\PageResource;
use App\Services\Page\PageServiceInterface;

class PageController extends Controller
{
    public function __construct
    (
        private PageServiceInterface $pageService
    )
    {
    }
    public function findByUrl(FindPageRequest $request)
    {
        return $this->dataResponse(new PageResource($this->pageService->findByUrl($request->get("url"))));
    }
}
