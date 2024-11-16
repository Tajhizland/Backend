<?php

namespace App\Services\HomePage;

use App\Repositories\Banner\BannerRepositoryInterface;
use App\Repositories\Brand\BrandRepositoryInterface;
use App\Repositories\Concept\ConceptRepositoryInterface;
use App\Repositories\HomepageCategory\HomepageCategoryRepositoryInterface;
use App\Repositories\New\NewRepositoryInterface;
use App\Repositories\PopularProduct\PopularProductRepositoryInterface;
use App\Repositories\Slider\SliderRepositoryInterface;
use App\Repositories\SpecialProduct\SpecialProductRepositoryInterface;
use App\Repositories\Vlog\VlogRepositoryInterface;

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
        private VlogRepositoryInterface              $vlogRepository,
        private NewRepositoryInterface              $newRepository,
    )
    {
    }


    public function buildData()
    {
        $popularProducts = $this->popularProductRepository->getWithProduct();
        $homepageCategories = $this->homepageCategoryRepository->getWithCategory();
        $specialProducts = $this->specialProductRepository->getWithProduct();
        $sliders = $this->sliderRepository->all();
        $concepts = $this->conceptRepository->getActiveWithCategory();
        $brands = $this->brandRepository->getAllActive();
        $banners = $this->bannerRepository->all();
        $lastNews = $this->newRepository->getLastActiveNews();
        $lastVlogs = $this->vlogRepository->getLastActives();
        return [
            "popularProducts" => $popularProducts,
            "homepageCategories" => $homepageCategories,
            "sliders" => $sliders,
            "concepts" => $concepts,
            "brands" => $brands,
            "news" => $lastNews,
            "vlogs" => $lastVlogs,
            "specialProducts" => $specialProducts,
            "banners" => $banners
        ];
    }
}
