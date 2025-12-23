<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Vlog\FindVlogByUrlRequest;
use App\Http\Requests\V1\Shop\Vlog\GetBlogByCategoryRequest;
use App\Http\Resources\V1\Banner\BannerCollection;
use App\Http\Resources\V1\Vlog\VlogCollection;
use App\Http\Resources\V1\Vlog\VlogResource;
use App\Http\Resources\V1\VlogCategory\VlogCategoryCollection;
use App\Http\Resources\V1\VlogCategory\VlogCategoryResource;
use App\Repositories\VlogCategory\VlogCategoryRepositoryInterface;
use App\Services\Banner\BannerServiceInterface;
use App\Services\Vlog\VlogServiceInterface;
use Illuminate\Http\Request;

class VlogController extends Controller
{
    public function __construct
    (
        private BannerServiceInterface          $bannerService,
        private VlogCategoryRepositoryInterface $vlogCategoryRepository,
        private VlogServiceInterface            $vlogService
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

    public function get(GetBlogByCategoryRequest $request)
    {
        $listing = new VlogCollection($this->vlogService->getByCategoryUrl($request->get("url"), $request->get("filter")));
        $mostViewed = new VlogCollection($this->vlogService->getMostViewed());
        $banners = new BannerCollection($this->bannerService->getVlogBanner());
        return $this->dataResponse([
            "listing" => $listing,
            "banner" => $banners,
            "mostViewed" => $mostViewed
        ]);
    }

    public function listing(Request $request)
    {
        $listing = new VlogCollection($this->vlogService->listing($request->get("filter")));
        $mostViewed = new VlogCollection($this->vlogService->getMostViewed());
        $banners = new BannerCollection($this->bannerService->getVlogBanner());
        $category = new VlogCategoryCollection($this->vlogCategoryRepository->getActiveList());
        return $this->dataResponse([
            "category" => $category,
            "listing" => $listing,
            "banner" => $banners,
            "mostViewed" => $mostViewed
        ]);
    }
}
