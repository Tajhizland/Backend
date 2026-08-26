<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Banner\BannerCollection;
use App\Http\Resources\Cast\CastCollection;
use App\Http\Resources\Cast\CastResource;
use App\Http\Resources\CastCategory\CastCategoryCollection;
use App\Repositories\Banner\BannerRepositoryInterface;
use App\Services\Cast\CastServiceInterface;
use App\Services\CastCategory\CastCategoryServiceInterface;
use Illuminate\Http\Request;

class CastController extends Controller
{
    public function __construct
    (
        private CastServiceInterface         $castService,
        private BannerRepositoryInterface    $bannerRepository,
        private CastCategoryServiceInterface $castCategoryService,
    )
    {
    }

    public function index(Request $request)
    {
        $banner = new BannerCollection($this->bannerRepository->getBannerByType("cast"));
        $listing = new CastCollection($this->castService->listing($request->get("filter")));
        $mostViewed = new CastCollection($this->castService->getMostViewed());
        $category = new CastCategoryCollection($this->castCategoryService->get());
        return $this->dataResponse([
            "category" => $category,
            "banner" => $banner,
            "listing" => $listing,
            "mostViewed" => $mostViewed
        ]);
    }

    public function find(Request $request)
    {
        $response = $this->castService->findByUrl($request->url);
        return $this->dataResponse(new CastResource($response));
    }
}
