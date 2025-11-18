<?php

namespace App\Services\HomePage;

use App\Repositories\Banner\BannerRepositoryInterface;
use App\Repositories\Brand\BrandRepositoryInterface;
use App\Repositories\Concept\ConceptRepositoryInterface;
use App\Repositories\HomepageCategory\HomepageCategoryRepositoryInterface;
use App\Repositories\New\NewRepositoryInterface;
use App\Repositories\PopularProduct\PopularProductRepositoryInterface;
use App\Repositories\Poster\PosterRepositoryInterface;
use App\Repositories\Price\PriceRepositoryInterface;
use App\Repositories\Slider\SliderRepositoryInterface;
use App\Repositories\SpecialProduct\SpecialProductRepositoryInterface;
use App\Repositories\Vlog\VlogRepositoryInterface;
use App\Services\Campaign\CampaignServiceInterface;

class HomePageService implements HomePageServiceInterface
{
    public function __construct
    (
        private PopularProductRepositoryInterface   $popularProductRepository,
        private HomepageCategoryRepositoryInterface $homepageCategoryRepository,
        private SliderRepositoryInterface           $sliderRepository,
        private SpecialProductRepositoryInterface   $specialProductRepository,
        private ConceptRepositoryInterface          $conceptRepository,
        private BrandRepositoryInterface            $brandRepository,
        private BannerRepositoryInterface           $bannerRepository,
        private VlogRepositoryInterface             $vlogRepository,
        private NewRepositoryInterface              $newRepository,
        private PosterRepositoryInterface           $posterRepository,
        private PriceRepositoryInterface            $priceRepository,
        private CampaignServiceInterface            $campaignService,
    )
    {
    }


    public function buildData()
    {
        $popularProducts = $this->popularProductRepository->getWithProduct();
        $homepageCategories = $this->homepageCategoryRepository->getWithCategory();
        $specialProducts = $this->specialProductRepository->getWithProduct();
        $desktopSliders = $this->sliderRepository->getActiveDesktopSlider();
        $mobileSliders = $this->sliderRepository->getActiveMobileSlider();
        $concepts = $this->conceptRepository->getActiveWithCategory();
        $brands = $this->brandRepository->getAllActive();
        $banners = $this->bannerRepository->getBannerByType("home_page");
        $banners2 = $this->bannerRepository->getBannerByType("home_page2");
        $banners3 = $this->bannerRepository->getBannerByType("home_page3");
        $banners4 = $this->bannerRepository->getBannerByType("home_page4");
        $banners5 = $this->bannerRepository->getBannerByType("home_page5");
        $bannersStock = $this->bannerRepository->getBannerByType("home_page6");
        $lastNews = $this->newRepository->getLastActiveNews();
//        $lastVlogs = $this->vlogRepository->getLastActives();
        $lastVlogs = $this->vlogRepository->getHomePageVlogs();
        $discountTimer = $this->priceRepository->findFirstExpireDiscount();
        $posters = $this->posterRepository->getHomepagePosters();
        $campaign = $this->campaignService->findActiveCampaign();
        $pendingCampaign = $this->campaignService->findPendingActiveCampaign();
        return [
            "campaign" => $campaign,
            "popularProducts" => $popularProducts,
            "discount" => $discountTimer,
            "homepageCategories" => $homepageCategories,
            "desktopSliders" => $desktopSliders,
            "mobileSliders" => $mobileSliders,
            "concepts" => $concepts,
            "brands" => $brands,
            "news" => $lastNews,
            "vlogs" => $lastVlogs,
            "specialProducts" => $specialProducts,
            "banners" => $banners,
            "banners2" => $banners2,
            "banners3" => $banners3,
            "banners4" => $banners4,
            "banners5" => $banners5,
            "bannersStock" => $bannersStock,
            "posters" => $posters,
            "pending_campaign" => $pendingCampaign,
        ];
    }
}
