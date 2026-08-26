<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Services\Brand\BrandServiceInterface;
use App\Services\Category\CategoryServiceInterface;
use App\Services\Guaranty\GuarantyServiceInterface;
use App\Services\Landing\LandingServiceInterface;
use App\Services\New\NewServiceInterface;
use App\Services\Product\ProductServiceInterface;
use App\Services\Vlog\VlogServiceInterface;
use App\Http\Resources\Sitemap\SitemapResource;

class SitemapController extends Controller
{
    public function __construct
    (
        private readonly ProductServiceInterface  $productService,
        private readonly CategoryServiceInterface $categoryService,
        private readonly BrandServiceInterface    $brandService,
        private readonly GuarantyServiceInterface $guarantyService,
        private readonly LandingServiceInterface  $landingService,
        private readonly NewServiceInterface      $newService,
        private readonly VlogServiceInterface     $vlogService
    )
    {
    }

    public function getProductSitemap()
    {
        return $this->dataResponseCollection(SitemapResource::collection($this->productService->getSitemapData()));
    }

    public function getCategorySitemap()
    {
        return $this->dataResponseCollection(SitemapResource::collection($this->categoryService->getSitemapData()));
    }

    public function getBrandSitemap()
    {
        return $this->dataResponseCollection(SitemapResource::collection($this->brandService->getSitemapData()));
    }

    public function getBlogSitemap()
    {
        return $this->dataResponseCollection(SitemapResource::collection($this->newService->getSitemapData()));
    }

    public function getVlogSitemap()
    {
        return $this->dataResponseCollection(SitemapResource::collection($this->vlogService->getSitemapData()));
    }

    public function getGuarantySitemap()
    {
        return $this->dataResponseCollection(SitemapResource::collection($this->guarantyService->getSitemapData()));
    }

    public function getLandingSitemap()
    {
        return $this->dataResponseCollection(SitemapResource::collection($this->landingService->getSitemapData()));
    }
}
