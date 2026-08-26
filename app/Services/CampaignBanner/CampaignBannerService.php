<?php

namespace App\Services\CampaignBanner;

use App\DTOs\CampaignBanner\CampaignBannerSortDto;
use App\DTOs\CampaignBanner\CampaignBannerStoreDto;
use App\DTOs\CampaignBanner\CampaignBannerUpdateDto;
use App\Repositories\CampaignBanner\CampaignBannerRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\S3\S3ServiceInterface;

readonly class CampaignBannerService implements CampaignBannerServiceInterface
{
    public function __construct(
        private CampaignBannerRepositoryInterface $campaignBannerRepository,
        private S3ServiceInterface                $s3Service
    )
    {
    }


    public function dataTable($campaign_id): mixed
    {
        return $this->campaignBannerRepository->dataTable($campaign_id);
    }

    public function delete(int $id): bool|null
    {
        return $this->campaignBannerRepository->delete($this->find($id));
    }

    public function store(CampaignBannerStoreDto $dto): mixed
    {
        return $this->campaignBannerRepository->create([
            "image" => $this->s3Service->upload($dto->image, "banner"),
            "type" => $dto->type,
            "campaign_id" => $dto->campaign_id,
            "url" => $dto->url,
        ]);
    }

    public function update(CampaignBannerUpdateDto $dto): bool
    {
        $banner = $this->find($dto->campaignBannerId);
        $imagePath = $banner->image;
        if ($dto->image) {
            $this->s3Service->remove("banner/" . $imagePath);
            $imagePath = $this->s3Service->upload($dto->image, "banner");
        }
        return $this->campaignBannerRepository->update($banner, [
            "image" => $imagePath,
            "type" => $dto->type,
            "url" => $dto->url,
        ]);
    }

    public function find(int $id): mixed
    {
        $banner = $this->campaignBannerRepository->find($id);
        if (!$banner) {
            throw new NotFoundHttpException();
        }
        return $banner;
    }

    public function getByType($type): mixed
    {
        return $this->campaignBannerRepository->getBannerByType($type);
    }

    public function sort(CampaignBannerSortDto $dto): bool
    {
        foreach ($dto->banner as $item) {
            $this->campaignBannerRepository->sort($item["id"], $item["sort"]);
        }
        return true;
    }
}
