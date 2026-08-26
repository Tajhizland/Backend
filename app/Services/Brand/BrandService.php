<?php

namespace App\Services\Brand;

use App\DTOs\Brand\BrandSortDto;
use App\DTOs\Brand\BrandStoreDto;
use App\DTOs\Brand\BrandUpdateDto;
use App\Repositories\Brand\BrandRepositoryInterface;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Services\Filter\FilterServiceInterface;
use App\Services\S3\S3ServiceInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class BrandService implements BrandServiceInterface
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

    public function list(): mixed
    {
        return $this->brandRepository->list();
    }

    public function listing($url, $filters): mixed
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

    public function dataTable(): mixed
    {
        return $this->brandRepository->dataTable();
    }

    public function find(int $id): mixed
    {
        $brand = $this->brandRepository->find($id);
        if (!$brand) {
            throw new NotFoundHttpException();
        }
        return $brand;
    }

    public function store(BrandStoreDto $dto): mixed
    {
        $imagePath = null;
        if ($dto->image) {
            $imagePath = $this->s3Service->upload($dto->image, "brand");
        }
        $bannerPath = null;
        if ($dto->banner) {
            $bannerPath = $this->s3Service->upload($dto->banner, "brand-banner");
        }
        return $this->brandRepository->create([
            "name" => $dto->name,
            "url" => $dto->url,
            "status" => $dto->status,
            "description" => $dto->description,
            "image" => $imagePath,
            "banner" => $bannerPath,
        ]);
    }

    public function update(BrandUpdateDto $dto): bool
    {
        $brand = $this->find($dto->brandId);
        $imagePath = $brand->image;
        if ($dto->image) {
            $this->s3Service->remove("brand/" . $brand->image);
            $imagePath = $this->s3Service->upload($dto->image, "brand");
        }
        $bannerPath = null;
        if ($dto->banner) {
            $this->s3Service->remove("brand-banner/" . $brand->banner);
            $bannerPath = $this->s3Service->upload($dto->banner, "brand-banner");
        }
        return $this->brandRepository->update($brand, [
            "name" => $dto->name,
            "url" => $dto->url,
            "status" => $dto->status,
            "description" => $dto->description,
            "image" => $imagePath,
            "banner" => $bannerPath,
        ]);
    }

    public function getAllActive(): mixed
    {
        return $this->brandRepository->getAllActive();
    }

    public function sort(BrandSortDto $dto): bool
    {
        foreach ($dto->brand as $item) {
            $this->brandRepository->sort($item["id"], $item["sort"]);
        }
        return true;
    }

    public function getSitemapData(): mixed
    {
        return $this->brandRepository->getSitemapData();
    }
}
