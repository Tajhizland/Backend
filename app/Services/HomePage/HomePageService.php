<?php

namespace App\Services\HomePage;

use App\Repositories\Banner\BannerRepositoryInterface;
use App\Repositories\Brand\BrandRepositoryInterface;
use App\Repositories\Concept\ConceptRepositoryInterface;
use App\Repositories\HomepageCategory\HomepageCategoryRepositoryInterface;
use App\Repositories\New\NewRepositoryInterface;
use App\Repositories\PopularProduct\PopularProductRepositoryInterface;
use App\Repositories\Poster\PosterRepositoryInterface;
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
        private VlogRepositoryInterface             $vlogRepository,
        private NewRepositoryInterface              $newRepository,
        private PosterRepositoryInterface           $posterRepository,
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
        $banners = $this->bannerRepository->getAll();
        $lastNews = $this->newRepository->getLastActiveNews();
        $lastVlogs = $this->vlogRepository->getLastActives();
        $posters=$this->posterRepository->getHomepagePosters();
        return [
            "popularProducts" => $popularProducts,
            "homepageCategories" => $homepageCategories,
            "desktopSliders" => $desktopSliders,
            "mobileSliders" => $mobileSliders,
            "concepts" => $concepts,
            "brands" => $brands,
            "news" => $lastNews,
            "vlogs" => $lastVlogs,
            "specialProducts" => $specialProducts,
            "banners" => $banners,
            "posters" => $posters,
        ];
    }
}
