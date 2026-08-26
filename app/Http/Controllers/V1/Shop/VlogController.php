<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\Vlog\FindVlogByUrlRequest;
use App\Http\Requests\Shop\Vlog\GetBlogByCategoryRequest;
use App\Http\Resources\Vlog\VlogResource;
use App\Http\Resources\VlogCategory\VlogCategoryResource;
use App\Repositories\VlogCategory\VlogCategoryRepositoryInterface;
use App\Services\Banner\BannerServiceInterface;
use App\Services\Vlog\VlogServiceInterface;
use Illuminate\Http\Request;
use App\Http\Resources\Banner\BannerResource;

class VlogController extends Controller
{
    public function __construct
    (
        private readonly BannerServiceInterface          $bannerService,
        private readonly VlogCategoryRepositoryInterface $vlogCategoryRepository,
        private readonly VlogServiceInterface            $vlogService
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
            "relatedVlogs" => VlogResource::collection($relatedVlogs)->response()->getData()
        ]);
    }

    public function get(GetBlogByCategoryRequest $request)
    {
        $listing = VlogResource::collection($this->vlogService->getByCategoryUrl($request->get("url"), $request->get("filter")))->response()->getData();
        $mostViewed = VlogResource::collection($this->vlogService->getMostViewed())->response()->getData();
        $banners = BannerResource::collection($this->bannerService->getVlogBanner())->response()->getData();
        $category = new VlogCategoryResource($this->vlogCategoryRepository->findByUrl($request->get("url")));
        $categorys = VlogCategoryResource::collection($this->vlogCategoryRepository->getActiveList())->response()->getData();
        return $this->dataResponse([
            "categorys" => $categorys,
            "category" => $category,
            "listing" => $listing,
            "banner" => $banners,
            "mostViewed" => $mostViewed
        ]);
    }

    public function listing(Request $request)
    {
        $listing = VlogResource::collection($this->vlogService->listing($request->get("filter")))->response()->getData();
        $mostViewed = VlogResource::collection($this->vlogService->getMostViewed())->response()->getData();
        $banners = BannerResource::collection($this->bannerService->getVlogBanner())->response()->getData();
        $category = VlogCategoryResource::collection($this->vlogCategoryRepository->getActiveList())->response()->getData();
        return $this->dataResponse([
            "category" => $category,
            "listing" => $listing,
            "banner" => $banners,
            "mostViewed" => $mostViewed
        ]);
    }
}
