<?php

namespace App\Services\Banner;

use App\DTOs\Banner\BannerSortDto;
use App\DTOs\Banner\BannerStoreDto;
use App\DTOs\Banner\BannerUpdateDto;
use App\Repositories\Banner\BannerRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\S3\S3ServiceInterface;

readonly class BannerService implements BannerServiceInterface
{
    public function __construct
    (
        private BannerRepositoryInterface $bannerRepository,
        private S3ServiceInterface        $s3Service,
    )
    {
    }

    public function dataTable(): mixed
    {
        return $this->bannerRepository->dataTable();
    }

    public function delete(int $id): bool|null
    {
        return $this->bannerRepository->delete($this->find($id));
    }

    public function store(BannerStoreDto $dto): mixed
    {
        return $this->bannerRepository->create([
            "image" => $this->s3Service->upload($dto->image, "banner"),
            "type" => $dto->type,
            "url" => $dto->url,
        ]);
    }

    public function update(BannerUpdateDto $dto): bool
    {
        $banner = $this->find($dto->bannerId);
        $imagePath = $banner->image;
        if ($dto->image) {
            $this->s3Service->remove("banner/" . $imagePath);
            $imagePath = $this->s3Service->upload($dto->image, "banner");
        }
        return $this->bannerRepository->update($banner, [
            "image" => $imagePath,
            "type" => $dto->type,
            "url" => $dto->url,
        ]);
    }

    public function find(int $id): mixed
    {
        $model = $this->bannerRepository->find($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        return $model;
    }
    public function getAll(): mixed
    {
        return $this->bannerRepository->all();
    }
    public function sort(BannerSortDto $dto): bool
    {
        foreach ($dto->banner as $item) {
            $this->bannerRepository->sort($item["id"], $item["sort"]);
        }
        return true;
    }

    public function getBlogBanner(): mixed
    {
        return $this->bannerRepository->getBannerByType("blog");
    }

    public function getVlogBanner(): mixed
    {
        return $this->bannerRepository->getBannerByType("vlog");
    }

    public function getBrandBanner(): mixed
    {
        return $this->bannerRepository->getBannerByType("brand");
    }

    public function getSpecialBanner(): mixed
    {
        return $this->bannerRepository->getBannerByType("special");
    }

    public function getDiscountedBanner(): mixed
    {
        return $this->bannerRepository->getBannerByType("discount");
    }
}
