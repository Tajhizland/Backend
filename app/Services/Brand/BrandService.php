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

        $categorys=$this->categoryRepository->getByBrandId($brand->id);

        return ["products" => $products, "brand" => $brand, "categories" => $categorys];
    }

    public function dataTable()
    {
        return $this->brandRepository->dataTable();
    }

    public function findById($id)
    {
        return $this->brandRepository->findOrFail($id);
    }

    public function storeBrand($name, $url, $status, $image, $description)
    {
        $imagePath = null;
        if ($image) {
            $imagePath = $this->s3Service->upload($image, "brand");
        }
        return $this->brandRepository->create(
            [
                "name" => $name,
                "url" => $url,
                "status" => $status,
                "description" => $description,
                "image" => $imagePath,
            ]
        );
    }

    public function updateBrand($id, $name, $url, $status, $image, $description)
    {
        $brand = $this->brandRepository->findOrFail($id);
        $imagePath = $brand->image;
        if ($image) {
            $this->s3Service->remove("brand/" . $brand->image);
            $imagePath = $this->s3Service->upload($image, "brand");
        }
        return $this->brandRepository
            ->update($brand,
                [
                    "name" => $name,
                    "url" => $url,
                    "status" => $status,
                    "description" => $description,
                    "image" => $imagePath,
                ]
            );
    }
}
