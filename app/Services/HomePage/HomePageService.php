<?php

namespace App\Services\HomePage;

use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Setting\SettingRepositoryInterface;

class HomePageService implements HomePageServiceInterface
{
    public function __construct
    (
        private ProductRepositoryInterface $productRepository,
        private SettingRepositoryInterface $settingRepository,
    )
    {
    }

    public function getData()
    {
        $setting = $this->settingRepository->findSetting();
        $homepageMostPopularProducts = [];
        $homepageHasDiscountProducts = [];
        $homepageNewProducts = [];
        $homepageCustomCategoryProducts = [];

        if ($setting->homepage_most_popular_products) {
            $homepageMostPopularProducts = $this->productRepository->getMostPopularProduct();
        }
        if ($setting->homepage_has_discount_products) {
            $homepageHasDiscountProducts = $this->productRepository->getHasDiscountProduct();
        }
        if ($setting->homepage_new_products) {
            $homepageNewProducts = $this->productRepository->getNewProduct();
        }
        if ($setting->homepage_custom_category_products) {
            $homepageCustomCategoryId = $setting->homepage_custom_category_id;
            $homepageCustomCategoryProducts = $this->productRepository->getCustomCategoryProduct($homepageCustomCategoryId);
        }

        return [
            "homepageMostPopularProducts" => $homepageMostPopularProducts,
            "homepageHasDiscountProducts" => $homepageHasDiscountProducts,
            "homepageNewProducts" => $homepageNewProducts,
            "homepageCustomCategoryProducts" => $homepageCustomCategoryProducts
        ];
    }
}
