<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Sitemap\SitemapCollection;
use App\Services\Brand\BrandServiceInterface;
use App\Services\Category\CategoryServiceInterface;
use App\Services\Guaranty\GuarantyServiceInterface;
use App\Services\Landing\LandingServiceInterface;
use App\Services\New\NewServiceInterface;
use App\Services\Product\ProductServiceInterface;
use App\Services\Vlog\VlogServiceInterface;

class SitemapController extends Controller
{
    public function __construct
    (
        private ProductServiceInterface  $productService,
        private CategoryServiceInterface $categoryService,
        private BrandServiceInterface    $brandService,
        private GuarantyServiceInterface $guarantyService,
        private LandingServiceInterface  $landingService,
        private NewServiceInterface      $newService,
        private VlogServiceInterface     $vlogService
    )
    {
    }

    public function getProductSitemap()
    {
        return $this->dataResponseCollection(new SitemapCollection($this->productService->getSitemapData()));
    }

    public function getCategorySitemap()
    {
        return $this->dataResponseCollection(new SitemapCollection($this->categoryService->getSitemapData()));
    }

    public function getBrandSitemap()
    {
        return $this->dataResponseCollection(new SitemapCollection($this->brandService->getSitemapData()));
    }

    public function getBlogSitemap()
    {
        return $this->dataResponseCollection(new SitemapCollection($this->newService->getSitemapData()));
    }

    public function getVlogSitemap()
    {
        return $this->dataResponseCollection(new SitemapCollection($this->vlogService->getSitemapData()));
    }

    public function getGuarantySitemap()
    {
        return $this->dataResponseCollection(new SitemapCollection($this->guarantyService->getSitemapData()));
    }

    public function getLandingSitemap()
    {
        return $this->dataResponseCollection(new SitemapCollection($this->landingService->getSitemapData()));
    }
}
