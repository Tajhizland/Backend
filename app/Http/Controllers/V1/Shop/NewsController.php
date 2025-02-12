<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Banner\BannerCollection;
use App\Http\Resources\V1\BlogCategory\BlogCategoryCollection;
use App\Http\Resources\V1\News\NewsCollection;
use App\Http\Resources\V1\News\NewsResource;
use App\Services\Banner\BannerServiceInterface;
use App\Services\BlogCategory\BlogCategoryServiceInterface;
use App\Services\New\NewServiceInterface;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function __construct
    (
        private BannerServiceInterface       $bannerService,
        private NewServiceInterface          $newService,
        private BlogCategoryServiceInterface $blogCategoryService
    ) { }

    public function paginate(Request $request)
    {
        $banners = new BannerCollection($this->bannerService->getBlogBanner());
        $listing = new NewsCollection($this->newService->activePaginate($request->get("filter")));
        $lastPost = new NewsCollection($this->newService->getLastPost());
        $category = new BlogCategoryCollection($this->blogCategoryService->list());
        return $this->dataResponse([
            "listing" => $listing,
            "banner" => $banners,
            "lastPost" => $lastPost,
            "category" => $category,
        ]);
    }

    public function findByUrl(Request $request)
    {
        return $this->dataResponse(new NewsResource($this->newService->findByUrl($request->url)));
    }
}
