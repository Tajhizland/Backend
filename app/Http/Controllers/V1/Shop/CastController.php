<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Banner\BannerCollection;
use App\Http\Resources\V1\Cast\CastCollection;
use App\Http\Resources\V1\Cast\CastResource;
use App\Http\Resources\V1\CastCategory\CastCategoryCollection;
use App\Http\Resources\V1\Vlog\VlogCollection;
use App\Http\Resources\V1\VlogCategory\VlogCategoryCollection;
use App\Services\Cast\CastServiceInterface;
use App\Services\CastCategory\CastCategoryServiceInterface;
use Illuminate\Http\Request;

class CastController extends Controller
{
    public function __construct
    (
        private CastServiceInterface $castService,
        private CastCategoryServiceInterface $castCategoryService,
    )
    {
    }

    public function index(Request $request)
    {
        $listing = new CastCollection($this->castService->listing($request->get("filter")));
        $mostViewed = new CastCollection($this->castService->getMostViewed());
        $category = new CastCategoryCollection($this->castCategoryService->get());
        return $this->dataResponse([
            "category" => $category,
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
