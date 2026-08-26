<?php

namespace App\Services\Landing;

use App\DTOs\Landing\LandingSetBannerDto;
use App\DTOs\Landing\LandingSetCategoryDto;
use App\DTOs\Landing\LandingSetProductDto;
use App\DTOs\Landing\LandingStoreDto;
use App\DTOs\Landing\LandingUpdateDto;
use App\Repositories\Landing\LandingRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repositories\LandingBanner\LandingBannerRepositoryInterface;
use App\Repositories\LandingCategory\LandingCategoryRepositoryInterface;
use App\Repositories\LandingProduct\LandingProductRepositoryInterface;
use App\Services\S3\S3ServiceInterface;

readonly class LandingService implements LandingServiceInterface
{
    public function __construct
    (
        private LandingRepositoryInterface         $landingRepository,
        private LandingCategoryRepositoryInterface $landingCategoryRepository,
        private LandingProductRepositoryInterface  $landingProductRepository,
        private LandingBannerRepositoryInterface   $landingBannerRepository,
        private S3ServiceInterface                 $s3Service,
    )
    {
    }

    public function store(LandingStoreDto $dto): mixed
    {
        return $this->landingRepository->create([
            "title" => $dto->title,
            "description" => $dto->description,
            "status" => $dto->status,
            "url" => $dto->url,
        ]);
    }

    public function update(LandingUpdateDto $dto): bool
    {
        $landing = $this->find($dto->landingId);
        return $this->landingRepository->update($landing, [
            "title" => $dto->title,
            "description" => $dto->description,
            "status" => $dto->status,
            "url" => $dto->url,
        ]);
    }

    public function find(int $id): mixed
    {
        $landing = $this->landingRepository->find($id);
        if (!$landing) {
            throw new NotFoundHttpException();
        }
        return $landing;
    }

    public function dataTable()
    {
        return $this->landingRepository->dataTable();
    }

    public function setProduct(LandingSetProductDto $dto): mixed
    {
        return $this->landingProductRepository->create(["landing_id" => $dto->landing_id, "product_id" => $dto->product_id]);
    }

    public function setCategory(LandingSetCategoryDto $dto): mixed
    {
        return $this->landingCategoryRepository->create(["landing_id" => $dto->landing_id, "category_id" => $dto->category_id]);
    }

    public function deleteProduct($id)
    {
        $landingProduct = $this->landingProductRepository->findOrFail($id);
        return $this->landingProductRepository->delete($landingProduct);
    }

    public function deleteCategory($id)
    {
        $landingCategory = $this->landingCategoryRepository->findOrFail($id);
        return $this->landingCategoryRepository->delete($landingCategory);
    }

    public function findByUrl($url)
    {
        $landing = $this->landingRepository->findByUrl($url);
        if(!$landing)
        {
            throw new NotFoundHttpException();
        }
        return $landing;
    }

    public function getProductByLanding($landingId)
    {

        return $this->landingProductRepository->getWithProduct($landingId);
    }

    public function getCategoryByLanding($landingId)
    {
        return $this->landingCategoryRepository->getWithCategory($landingId);
    }

    public function getBanner($landingId)
    {
        return $this->landingBannerRepository->getByLandingId($landingId);
    }

    public function deleteBanner($id)
    {
        $banner = $this->landingBannerRepository->findOrFail($id);
        $imagePath = $banner->image;
        $this->s3Service->remove("landing-banner/" . $imagePath);
        return $this->landingBannerRepository->delete($banner);
    }

    public function setBanner(LandingSetBannerDto $dto): mixed
    {
        return $this->landingBannerRepository->create([
            "url" => $dto->url,
            "landing_id" => $dto->landing_id,
            "slider" => $dto->slider,
            "image" => $this->s3Service->upload($dto->image, "landing-banner"),
        ]);
    }

    public function getSitemapData()
    {
        return $this->landingRepository->getSitemapData();
    }

}
