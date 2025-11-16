<?php

namespace App\Services\Campaign;

use App\Repositories\Campaign\CampaignRepositoryInterface;
use App\Services\S3\S3ServiceInterface;

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

    public function store($title, $status, $color, $startDate, $endDate, $logo, $banner)
    {
        $logoPath = $this->s3Service->upload($logo, "campaign");
        $bannerPath = null;
        if ($banner) {
            $bannerPath = $this->s3Service->upload($banner, "campaign");
        }
        return $this->campaignRepository->create([
            "title" => $title,
            "status" => $status,
            "color" => $color,
            "start_date" => $startDate,
            "end_date" => $endDate,
            "logo" => $logoPath,
            "banner" => $bannerPath,
        ]);
    }

    public function update($id, $title, $status, $color, $startDate, $endDate, $logo, $banner)
    {
        $campaign = $this->campaignRepository->findOrFail($id);
        $logoPath = $campaign->logo;
        $bannerPath = $campaign->banner;
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
            "start_date" => $startDate,
            "end_date" => $endDate,
            "logo" => $logoPath,
            "banner" => $bannerPath,
        ]);
    }

    public function findActiveCampaign()
    {
        return $this->campaignRepository->findActiveCampaign();
    }
}
