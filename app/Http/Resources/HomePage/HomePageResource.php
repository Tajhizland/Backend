<?php

namespace App\Http\Resources\HomePage;

use App\DTOs\HomePage\HomePageData;
use App\Http\Resources\Banner\BannerResource;
use App\Http\Resources\Brand\BrandResource;
use App\Http\Resources\Campaign\CampaignResource;
use App\Http\Resources\Concept\ConceptResource;
use App\Http\Resources\DiscountItem\DiscountItemResource;
use App\Http\Resources\Poster\PosterResource;
use App\Http\Resources\Product\Card\ProductCardResource;
use App\Http\Resources\Slider\SliderResource;
use App\Http\Resources\TrustedBrand\TrustedBrandResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * پاسخ اندپوینت GET /api/v1/homepage
 *
 * @property-read HomePageData $resource
 */
class HomePageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = $this->resource;

        return [
            "campaign" => $data->campaign ? new CampaignResource($data->campaign) : null,
            "pending_campaign" => $data->pendingCampaign ? new CampaignResource($data->pendingCampaign) : null,
            "discount" => $data->discountTimer ? new DiscountItemResource($data->discountTimer) : null,

            "desktopSliders" => SliderResource::collection($data->sliders("desktop")),
            "mobileSliders" => SliderResource::collection($data->sliders("mobile")),

            "banners" => BannerResource::collection($data->banners("home_page")),
            "banners2" => BannerResource::collection($data->banners("home_page2")),
            "banners3" => BannerResource::collection($data->banners("home_page3")),
            "banners4" => BannerResource::collection($data->banners("home_page4")),
            "banners5" => BannerResource::collection($data->banners("home_page5")),
            "bannersStock" => BannerResource::collection($data->banners("home_page6")),
            "bannersCast" => BannerResource::collection($data->banners("homepage_cast")),

            "topDiscountedProducts" => ProductCardResource::collection($data->topDiscountedProducts),
            "specialProducts" => ProductCardResource::collection($data->specialProducts),
            "homepageCategories" => HomePageCategoryResource::collection($data->homePageCategories),

            "concepts" => ConceptResource::collection($data->concepts),
            "brands" => BrandResource::collection($data->brands),
            "trustedBrands" => TrustedBrandResource::collection($data->trustedBrands),
            "posters" => PosterResource::collection($data->posters),

            "vlogs" => HomePageVlogResource::collection($data->vlogs),
            "news" => HomePageNewsResource::collection($data->news),
        ];
    }
}
