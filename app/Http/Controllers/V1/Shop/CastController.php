<?php

namespace App\Http\Controllers\V1\Shop;

use App\DTOs\Cast\CastListingDto;
use App\Http\Requests\Shop\Cast\CastListingRequest;
use App\Http\Requests\Shop\Cast\FindCastRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\Cast\CastResource;
use App\Repositories\Banner\BannerRepositoryInterface;
use App\Services\Cast\CastServiceInterface;
use App\Services\CastCategory\CastCategoryServiceInterface;
use Illuminate\Http\Request;
use App\Http\Resources\CastCategory\CastCategoryResource;
use App\Http\Resources\Banner\BannerResource;

class CastController extends Controller
{
    public function __construct
    (
        private readonly CastServiceInterface         $castService,
        private readonly BannerRepositoryInterface    $bannerRepository,
        private readonly CastCategoryServiceInterface $castCategoryService,
    )
    {
    }

    public function index(CastListingRequest $request)
    {
        $banner = BannerResource::collection($this->bannerRepository->getBannerByType("cast"))->response()->getData();
        $dto = new CastListingDto(...$request->validated());
        $listing = CastResource::collection($this->castService->listing($dto->filter))->response()->getData();
        $mostViewed = CastResource::collection($this->castService->getMostViewed())->response()->getData();
        $category = CastCategoryResource::collection($this->castCategoryService->get())->response()->getData();
        return $this->dataResponse([
            "category" => $category,
            "banner" => $banner,
            "listing" => $listing,
            "mostViewed" => $mostViewed
        ]);
    }

    public function find(FindCastRequest $request)
    {
        $response = $this->castService->findByUrl($request->url);
        return $this->dataResponse(new CastResource($response));
    }
}
