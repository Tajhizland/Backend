<?php

namespace App\Services\HomePage;

use App\Repositories\Brand\BrandRepositoryInterface;
use App\Repositories\Concept\ConceptRepositoryInterface;
use App\Repositories\HomepageCategory\HomepageCategoryRepositoryInterface;
use App\Repositories\PopularCategory\PopularCategoryRepositoryInterface;
use App\Repositories\PopularProduct\PopularProductRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Setting\SettingRepositoryInterface;
use App\Repositories\Slider\SliderRepositoryInterface;
use App\Repositories\SpecialProduct\SpecialProductRepositoryInterface;

class HomePageService implements HomePageServiceInterface
{
    public function __construct
    (
        private ProductRepositoryInterface          $productRepository,
        private SettingRepositoryInterface          $settingRepository,
        private PopularProductRepositoryInterface   $popularProductRepository,
        private PopularCategoryRepositoryInterface  $popularCategoryRepository,
        private HomepageCategoryRepositoryInterface $homepageCategoryRepository,
        private SliderRepositoryInterface           $sliderRepository,
        private SpecialProductRepositoryInterface   $specialProductRepository,
        private ConceptRepositoryInterface          $conceptRepository,
        private BrandRepositoryInterface          $brandRepository
    )
    {
    }


    public function buildData()
    {
        $popularProducts = $this->popularProductRepository->getWithProduct();
        $popularCategories = $this->popularCategoryRepository->getWithCategory();
        $homepageCategories = $this->homepageCategoryRepository->getWithCategory();
        $specialProducts = $this->specialProductRepository->getWithProduct();
        $sliders = $this->sliderRepository->all();
        $concepts = $this->conceptRepository->all();
        $brands = $this->brandRepository->getAllActive();
         return [
            "popularProducts" => $popularProducts,
            "popularCategories" => $popularCategories,
            "homepageCategories" => $homepageCategories,
            "sliders" => $sliders,
            "concepts" => $concepts,
            "brands" => $brands,
            "specialProducts" => $specialProducts
        ];
    }
}
