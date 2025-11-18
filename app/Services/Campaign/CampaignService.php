<?php

namespace App\Services\Campaign;

use App\Repositories\Campaign\CampaignRepositoryInterface;
use App\Services\S3\S3ServiceInterface;
use Carbon\Carbon;

class CampaignService implements CampaignServiceInterface
{
    public function __construct
    (
        private CampaignRepositoryInterface $campaignRepository,
        private S3ServiceInterface          $s3Service,
    )
    {
    }

    public function dataTable()
    {
        return $this->campaignRepository->dataTable();
    }

    public function find($id)
    {
        return $this->campaignRepository->findOrFail($id);

    }

    public function store($title, $status, $color, $startDate, $endDate, $logo, $banner, $background_color, $discount_logo)
    {
        $logoPath = $this->s3Service->upload($logo, "campaign");
        $discountLogoPath = $this->s3Service->upload($discount_logo, "campaign");
        $bannerPath = null;
        if ($banner) {
            $bannerPath = $this->s3Service->upload($banner, "campaign");
        }
        return $this->campaignRepository->create([
            "title" => $title,
            "status" => $status,
            "color" => $color,
            "start_date" => Carbon::parse($startDate),
            "end_date" => Carbon::parse($endDate),
            "logo" => $logoPath,
            "banner" => $bannerPath,
            "background_color" => $background_color,
            "discount_logo" => $discountLogoPath,
        ]);
    }

    public function update($id, $title, $status, $color, $startDate, $endDate, $logo, $banner, $background_color, $discount_logo)
    {
        $campaign = $this->campaignRepository->findOrFail($id);
        $logoPath = $campaign->logo;
        $bannerPath = $campaign->banner;
        $discountLogoPath = $campaign->discount_logo;
        if ($discount_logo) {
            $this->s3Service->remove("campaign/$discountLogoPath");
            $discountLogoPath = $this->s3Service->upload($discount_logo, "campaign");
        }
        if ($logo) {
            $this->s3Service->remove("campaign/$logoPath");
            $logoPath = $this->s3Service->upload($logo, "campaign");
        }
        if ($banner) {
            $this->s3Service->remove("campaign/$bannerPath");
            $bannerPath = $this->s3Service->upload($banner, "campaign");
        }
        return $this->campaignRepository->update($campaign, [
            "title" => $title,
            "status" => $status,
            "color" => $color,
            "start_date" => Carbon::parse($startDate),
            "end_date" => Carbon::parse($endDate),
            "logo" => $logoPath,
            "banner" => $bannerPath,
            "background_color" => $background_color,
            "discount_logo" => $discountLogoPath,
        ]);
    }

    public function findActiveCampaign()
    {
        return $this->campaignRepository->findActiveCampaign();
    }

    public function findPendingActiveCampaign()
    {
        return $this->campaignRepository->findPendingActiveCampaign();
    }
}
