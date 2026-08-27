<?php

namespace App\Services\HomePage;

use App\DTOs\HomePage\HomePageData;
use App\Http\Resources\HomePage\HomePageResource;
use App\Repositories\Banner\BannerRepositoryInterface;
use App\Repositories\Brand\BrandRepositoryInterface;
use App\Repositories\Concept\ConceptRepositoryInterface;
use App\Repositories\HomepageCategory\HomepageCategoryRepositoryInterface;
use App\Repositories\New\NewRepositoryInterface;
use App\Repositories\Poster\PosterRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\RandomProductCategory\RandomProductCategoryRepositoryInterface;
use App\Repositories\Slider\SliderRepositoryInterface;
use App\Repositories\SpecialProduct\SpecialProductRepositoryInterface;
use App\Repositories\Vlog\VlogRepositoryInterface;
use App\Services\Campaign\CampaignServiceInterface;
use App\Services\DiscountItem\DiscountItemServiceInterface;
use App\Services\TrustedBrand\TrustedBrandServiceInterface;
use Illuminate\Support\Facades\Cache;

readonly class HomePageService implements HomePageServiceInterface
{
    /**
     * انواع بنری که صفحه اصلی لازم دارد؛ همه با یک کوئری خوانده می‌شوند.
     */
    private const BANNER_TYPES = [
        "home_page",
        "home_page2",
        "home_page3",
        "home_page4",
        "home_page5",
        "home_page6",
        "homepage_cast",
    ];

    public function __construct
    (
        private HomepageCategoryRepositoryInterface      $homepageCategoryRepository,
        private RandomProductCategoryRepositoryInterface $randomProductCategoryRepository,
        private SliderRepositoryInterface           $sliderRepository,
        private SpecialProductRepositoryInterface   $specialProductRepository,
        private ConceptRepositoryInterface          $conceptRepository,
        private BrandRepositoryInterface            $brandRepository,
        private BannerRepositoryInterface           $bannerRepository,
        private VlogRepositoryInterface             $vlogRepository,
        private NewRepositoryInterface              $newRepository,
        private PosterRepositoryInterface           $posterRepository,
        private ProductRepositoryInterface          $productRepository,
        private CampaignServiceInterface            $campaignService,
        private DiscountItemServiceInterface        $discountItemService,
        private TrustedBrandServiceInterface        $trustedBrandService,
    )
    {
    }

    public function payload(): mixed
    {
        $ttl = (int)config("settings.home_page.cache_ttl");

        // کش خاموش: مستقیم خود Resource برگردانده می‌شود تا json_encode اضافه نخوریم.
        if ($ttl <= 0) {
            return new HomePageResource($this->buildData());
        }

        return Cache::remember($this->cacheKey(), $ttl, fn() => $this->buildPayload());
    }

    public function buildData(): HomePageData
    {
        $config = config("settings.home_page");

        return new HomePageData(
            campaign: $this->campaignService->findActiveCampaign(),
            pendingCampaign: $this->campaignService->findPendingActiveCampaign(),
            discountTimer: $this->discountItemService->findFirstExpireDiscount(),
            topDiscountedProducts: $this->productRepository->getTopDiscountedProductCards($config["top_discount_limit"]),
            specialProducts: $this->specialProductRepository->getHomepageProductCards(),
            randomProducts: $this->randomProductCategoryRepository->getRandomProductCards($config["random_product_limit"]),
            homePageCategories: $this->homepageCategoryRepository->getWithCategory($config["category_product_limit"]),
            concepts: $this->conceptRepository->getActiveWithCategory(),
            brands: $this->brandRepository->getAllActive($config["brand_limit"]),
            trustedBrands: $this->trustedBrandService->get(),
            vlogs: $this->vlogRepository->getHomePageVlogs($config["vlog_limit"]),
            news: $this->newRepository->getLastActiveNews($config["news_limit"]),
            posters: $this->posterRepository->getHomepagePosters(),
            sliderGroups: $this->sliderRepository->getActiveGroupedByType(),
            bannerGroups: $this->bannerRepository->getGroupedByTypes(self::BANNER_TYPES),
        );
    }

    public function flushCache(): void
    {
        Cache::forget($this->cacheKey());
    }

    /**
     * خروجی نهایی به صورت آرایه‌ی خالص.
     *
     * از toJson استفاده می‌کنیم چون jsonSerialize فقط یک سطح را resolve می‌کند و
     * Resource های تودرتو به صورت آبجکت باقی می‌مانند (قابل کش شدن نیستند).
     */
    private function buildPayload(): array
    {
        return json_decode((new HomePageResource($this->buildData()))->toJson(), true);
    }

    private function cacheKey(): string
    {
        return (string)config("settings.home_page.cache_key", "shop:home_page:v1");
    }
}
