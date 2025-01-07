<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Brand\BrandCollection;
use App\Http\Resources\V1\Category\CategoryCollection;
use App\Http\Resources\V1\News\NewsCollection;
use App\Http\Resources\V1\Product\ProductCollection;
use App\Http\Resources\V1\Vlog\VlogCollection;
use App\Services\Brand\BrandServiceInterface;
use App\Services\Category\CategoryServiceInterface;
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
        private NewServiceInterface      $newService,
        private VlogServiceInterface     $vlogService
    )
    {
    }

    public function getProductSitemap()
    {
        return $this->dataResponseCollection(new ProductCollection($this->productService->getSitemapData()));
    }

    public function getCategorySitemap()
    {
        return $this->dataResponseCollection(new CategoryCollection($this->categoryService->getSitemapData()));
    }

    public function getBrandSitemap()
    {
        return $this->dataResponseCollection(new BrandCollection($this->brandService->getSitemapData()));
    }

    public function getBlogSitemap()
    {
        return $this->dataResponseCollection(new NewsCollection($this->newService->getSitemapData()));
    }

    public function getVlogSitemap()
    {
        return $this->dataResponseCollection(new VlogCollection($this->vlogService->getSitemapData()));
    }
}
