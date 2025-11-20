<?php

namespace App\Services\CampaignBanner;

use App\Repositories\CampaignBanner\CampaignBannerRepositoryInterface;
use App\Services\S3\S3ServiceInterface;

class CampaignBannerService implements CampaignBannerServiceInterface
{
    public function __construct(
        private CampaignBannerRepositoryInterface $campaignBannerRepository,
        private S3ServiceInterface                $s3Service
    )
    {
    }


    public function dataTable($campaign_id)
    {
        return $this->campaignBannerRepository->dataTable($campaign_id);
    }

    public function delete($id)
    {
        $banner = $this->campaignBannerRepository->findOrFail($id);
        return $this->campaignBannerRepository->delete($banner);
    }

    public function create($image, $url, $type, $campaign_id)
    {
        $imagePath = $this->s3Service->upload($image, "banner");
        return $this->campaignBannerRepository->create([
            "image" => $imagePath,
            "type" => $type,
            "campaign_id" => $campaign_id,
            "url" => $url
        ]);
    }

    public function update($id, $image, $url, $type)
    {
        $banner = $this->campaignBannerRepository->findOrFail($id);
        $imagePath = $banner->image;
        if ($image) {
            $this->s3Service->remove("banner/" . $imagePath);
            $imagePath = $this->s3Service->upload($image, "banner");
        }
        return $this->campaignBannerRepository->update($banner, [
            "image" => $imagePath,
            "type" => $type,
            "url" => $url
        ]);
    }

    public function findById($id)
    {
        return $this->campaignBannerRepository->findOrFail($id);
    }

    public function getByType($type)
    {
        return $this->campaignBannerRepository->getBannerByType($type);
    }

    public function sort($array)
    {
        foreach ($array as $item) {
            $this->campaignBannerRepository->sort($item["id"], $item["sort"]);
        }
        return true;
    }
}
