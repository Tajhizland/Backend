<?php

namespace App\Services\CampaignSlider;

use App\Repositories\CampaignSlider\CampaignSliderRepositoryInterface;
use App\Services\S3\S3ServiceInterface;

class CampaignSliderService implements CampaignSliderServiceInterface
{
    public function __construct
    (
        private CampaignSliderRepositoryInterface $campaignSliderRepository,
        private S3ServiceInterface                $s3Service
    )
    {
    }

    public function store($title, $url, $status, $type, $image, $campaignId)
    {
        $imagePath = $this->s3Service->upload($image, "slider");
        return $this->campaignSliderRepository->create(["campaign_id" => $campaignId, "title" => $title, "url" => $url, "image" => $imagePath, "type" => $type, "status" => $status]);

    }

    public function find($id)
    {

    }

    public function update($id, $title, $url, $status, $type, $image)
    {
        $slider = $this->campaignSliderRepository->findOrFail($id);
        $imagePath = $slider->image;
        if ($image) {
            $this->s3Service->remove("slider/" . $slider->image);
            $imagePath = $this->s3Service->upload($image, "slider");
        }
        return $this->campaignSliderRepository->update($slider, ["title" => $title, "url" => $url, "image" => $imagePath, "status" => $status, "type" => $type]);

    }

    public function getAllDesktop()
    {
        return $this->campaignSliderRepository->getAllDesktop();
    }

    public function getAllMobile()
    {
        return $this->campaignSliderRepository->getAllMobile();
    }

    public function sort($sliders)
    {
        foreach ($sliders as $item) {
            $this->campaignSliderRepository->sort($item["id"], $item["sort"]);
        }
        return true;
    }

    public function getByCampaignId($campaignId)
    {

    }
}
