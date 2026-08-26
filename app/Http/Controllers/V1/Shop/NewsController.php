<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\News\NewsResource;
use App\Services\Banner\BannerServiceInterface;
use App\Services\BlogCategory\BlogCategoryServiceInterface;
use App\Services\New\NewServiceInterface;
use Illuminate\Http\Request;
use App\Http\Resources\BlogCategory\BlogCategoryResource;
use App\Http\Resources\Banner\BannerResource;

class NewsController extends Controller
{
    public function __construct
    (
        private readonly BannerServiceInterface       $bannerService,
        private readonly NewServiceInterface          $newService,
        private readonly BlogCategoryServiceInterface $blogCategoryService
    ) { }

    public function paginate(Request $request)
    {
        $banners = BannerResource::collection($this->bannerService->getBlogBanner())->response()->getData();
        $listing = NewsResource::collection($this->newService->activePaginate($request->get("filter")))->response()->getData();
        $lastPost = NewsResource::collection($this->newService->getLastPost())->response()->getData();
        $category = BlogCategoryResource::collection($this->blogCategoryService->list())->response()->getData();
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
