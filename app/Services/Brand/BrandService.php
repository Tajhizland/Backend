<?php

namespace App\Services\Brand;

use App\Repositories\Brand\BrandRepositoryInterface;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Services\Filter\FilterServiceInterface;
use App\Services\S3\S3ServiceInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BrandService implements BrandServiceInterface
{

    public function __construct(
        private BrandRepositoryInterface    $brandRepository,
        private ProductRepositoryInterface  $productRepository,
        private FilterServiceInterface      $filterService,
        private CategoryRepositoryInterface $categoryRepository,
        private S3ServiceInterface          $s3Service
    )
    {
    }

    public function list()
    {
        return $this->brandRepository->list();
    }

    public function listing($url, $filters)
    {
        $brand = $this->brandRepository->findByUrl($url);
        if (!$brand) {
            throw new NotFoundHttpException();
        }
        $productsQuery = $this->productRepository->activeProductByBrandQuery($brand->id);
        $productsQuery = $this->filterService->apply($productsQuery, $filters);
        $products = $this->productRepository->paginated($productsQuery);

        $categories = $this->categoryRepository->getByBrandId($brand->id);
        return ["products" => $products, "brand" => $brand, "categories" => $categories];
    }

    public function dataTable()
    {
        return $this->brandRepository->dataTable();
    }

    public function findById($id)
    {
        return $this->brandRepository->findOrFail($id);
    }

    public function storeBrand($name, $url, $status, $image, $banner, $description)
    {
        $imagePath = null;
        if ($image) {
            $imagePath = $this->s3Service->upload($image, "brand");
        }
        $bannerPath = null;
        if ($banner) {
            $bannerPath = $this->s3Service->upload($banner, "brand-banner");
        }
        return $this->brandRepository->create(
            [
                "name" => $name,
                "url" => $url,
                "status" => $status,
                "description" => $description,
                "image" => $imagePath,
                "banner" => $bannerPath,
            ]
        );
    }

    public function updateBrand($id, $name, $url, $status, $image, $banner, $description)
    {
        $brand = $this->brandRepository->findOrFail($id);
        $imagePath = $brand->image;
        if ($image) {
            $this->s3Service->remove("brand/" . $brand->image);
            $imagePath = $this->s3Service->upload($image, "brand");
        }
        $bannerPath = null;
        if ($banner) {
            $this->s3Service->remove("brand-banner/" . $brand->banner);
            $bannerPath = $this->s3Service->upload($banner, "brand-banner");
        }
        return $this->brandRepository
            ->update($brand,
                [
                    "name" => $name,
                    "url" => $url,
                    "status" => $status,
                    "description" => $description,
                    "image" => $imagePath,
                    "banner" => $bannerPath,
                ]
            );
    }

    public function getAllActive()
    {
        return $this->brandRepository->getAllActive();
    }

    public function sort($brands)
    {
        foreach ($brands as $item) {
            $this->brandRepository->sort($item["id"], $item["sort"]);
        }
        return true;
    }

    public function getSitemapData()
    {
        return $this->brandRepository->getSitemapData();
    }
}
