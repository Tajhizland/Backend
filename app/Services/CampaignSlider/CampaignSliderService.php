<?php

namespace App\Services\CampaignSlider;

use App\DTOs\CampaignSlider\CampaignSliderSortDto;
use App\DTOs\CampaignSlider\CampaignSliderStoreDto;
use App\DTOs\CampaignSlider\CampaignSliderUpdateDto;
use App\Repositories\CampaignSlider\CampaignSliderRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\S3\S3ServiceInterface;

readonly class CampaignSliderService implements CampaignSliderServiceInterface
{
    public function __construct
    (
        private CampaignSliderRepositoryInterface $campaignSliderRepository,
        private S3ServiceInterface                $s3Service
    )
    {
    }

    public function store(CampaignSliderStoreDto $dto): mixed
    {
        return $this->campaignSliderRepository->create([
            "campaign_id" => $dto->campaign_id,
            "title" => $dto->title,
            "url" => $dto->url,
            "image" => $this->s3Service->upload($dto->image, "slider"),
            "type" => $dto->type,
            "status" => $dto->status,
        ]);
    }

    public function find(int $id): mixed
    {
        $slider = $this->campaignSliderRepository->find($id);
        if (!$slider) {
            throw new NotFoundHttpException();
        }
        return $slider;
    }

    public function update(CampaignSliderUpdateDto $dto): bool
    {
        $slider = $this->find($dto->campaignSliderId);
        $imagePath = $slider->image;
        if ($dto->image) {
            $this->s3Service->remove("slider/" . $slider->image);
            $imagePath = $this->s3Service->upload($dto->image, "slider");
        }
        return $this->campaignSliderRepository->update($slider, [
            "title" => $dto->title,
            "url" => $dto->url,
            "image" => $imagePath,
            "status" => $dto->status,
            "type" => $dto->type,
        ]);
    }

    public function getAllDesktop(): mixed
    {
        return $this->campaignSliderRepository->getAllDesktop();
    }

    public function getAllMobile(): mixed
    {
        return $this->campaignSliderRepository->getAllMobile();
    }

    public function sort(CampaignSliderSortDto $dto): bool
    {
        foreach ($dto->slider as $item) {
            $this->campaignSliderRepository->sort($item["id"], $item["sort"]);
        }
        return true;
    }

    public function getByCampaignId($campaignId): mixed
    {
        return $this->campaignSliderRepository->getByCampaignId($campaignId);
    }

    public function delete(int $id): bool|null
    {
        return $this->campaignSliderRepository->delete($this->find($id));
    }
}
