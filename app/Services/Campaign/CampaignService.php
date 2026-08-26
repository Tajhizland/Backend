<?php

namespace App\Services\Campaign;

use App\DTOs\Campaign\CampaignStoreDto;
use App\DTOs\Campaign\CampaignUpdateDto;
use App\Repositories\Campaign\CampaignRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\S3\S3ServiceInterface;
use Carbon\Carbon;

readonly class CampaignService implements CampaignServiceInterface
{
    public function __construct
    (
        private CampaignRepositoryInterface $campaignRepository,
        private S3ServiceInterface          $s3Service,
    )
    {
    }

    public function dataTable(): mixed
    {
        return $this->campaignRepository->dataTable();
    }

    public function find(int $id): mixed
    {
        $campaign = $this->campaignRepository->find($id);
        if (!$campaign) {
            throw new NotFoundHttpException();
        }
        return $campaign;
    }

    public function store(CampaignStoreDto $dto): mixed
    {
        $logoPath = $this->s3Service->upload($dto->logo, "campaign");
        $discountLogoPath = $this->s3Service->upload($dto->discount_logo, "campaign");
        $bannerPath = null;
        if ($dto->banner) {
            $bannerPath = $this->s3Service->upload($dto->banner, "campaign");
        }
        return $this->campaignRepository->create([
            "title" => $dto->title,
            "status" => $dto->status,
            "color" => $dto->color,
            "start_date" => Carbon::parse($dto->start_date),
            "end_date" => Carbon::parse($dto->end_date),
            "logo" => $logoPath,
            "banner" => $bannerPath,
            "background_color" => $dto->background_color,
            "discount_logo" => $discountLogoPath,
        ]);
    }

    public function update(CampaignUpdateDto $dto): bool
    {
        $campaign = $this->find($dto->campaignId);
        $logoPath = $campaign->logo;
        $bannerPath = $campaign->banner;
        $discountLogoPath = $campaign->discount_logo;
        if ($dto->discount_logo) {
            $this->s3Service->remove("campaign/$discountLogoPath");
            $discountLogoPath = $this->s3Service->upload($dto->discount_logo, "campaign");
        }
        if ($dto->logo) {
            $this->s3Service->remove("campaign/$logoPath");
            $logoPath = $this->s3Service->upload($dto->logo, "campaign");
        }
        if ($dto->banner) {
            $this->s3Service->remove("campaign/$bannerPath");
            $bannerPath = $this->s3Service->upload($dto->banner, "campaign");
        }
        return $this->campaignRepository->update($campaign, [
            "title" => $dto->title,
            "status" => $dto->status,
            "color" => $dto->color,
            "start_date" => Carbon::parse($dto->start_date),
            "end_date" => Carbon::parse($dto->end_date),
            "logo" => $logoPath,
            "banner" => $bannerPath,
            "background_color" => $dto->background_color,
            "discount_logo" => $discountLogoPath,
        ]);
    }

    public function findActiveCampaign(): mixed
    {
        return $this->campaignRepository->findActiveCampaign();
    }

    public function findPendingActiveCampaign(): mixed
    {
        return $this->campaignRepository->findPendingActiveCampaign();
    }
}
