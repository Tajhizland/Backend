<?php

namespace App\Services\Slider;

use App\Repositories\Slider\SliderRepositoryInterface;
use App\Services\S3\S3ServiceInterface;

class SliderService implements SliderServiceInterface
{
    public function __construct
    (
        private SliderRepositoryInterface $sliderRepository,
        private S3ServiceInterface        $s3Service,
    )
    {
    }

    public function findById($id)
    {
        return $this->sliderRepository->findOrFail($id);
    }

    public function dataTable()
    {
        return $this->sliderRepository->dataTable();
    }

    public function store($title, $url, $status, $type, $image)
    {
        $imagePath = $this->s3Service->upload($image, "slider");
        return $this->sliderRepository->create(["title" => $title, "url" => $url, "image" => $imagePath, "type" => $type, "status" => $status]);
    }

    public function update($id, $title, $url, $status, $type, $image)
    {
        $slider = $this->sliderRepository->findOrFail($id);
        $imagePath = $slider->image;
        if ($image) {
            $this->s3Service->remove("slider/" . $slider->image);
            $imagePath = $this->s3Service->upload($image, "slider");
        }
        return $this->sliderRepository->update($slider, ["title" => $title, "url" => $url, "image" => $imagePath, "status" => $status, "type" => $type]);
    }
}
