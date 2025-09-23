<?php

namespace App\Services\Sample;

use App\Repositories\Sample\SampleRepositoryInterface;
use App\Repositories\SampleImage\SampleImageRepositoryInterface;
use App\Repositories\SampleVideo\SampleVideoRepositoryInterface;
use App\Services\S3\S3ServiceInterface;

class SampleService implements SampleServiceInterface
{
    public function __construct
    (
        private SampleRepositoryInterface      $sampleRepository,
        private SampleImageRepositoryInterface $sampleImageRepository,
        private SampleVideoRepositoryInterface $sampleVideoRepository,
        private S3ServiceInterface             $s3Service
    )
    {
    }

    public function find()
    {
        return $this->sampleRepository->first();
    }

    public function update($content)
    {
        $sample = $this->sampleRepository->first();
        return $this->sampleRepository->update($sample, [
            "content" => $content
        ]);
    }

    public function uploadImage($image)
    {
        $imagePath = $this->s3Service->upload($image, "sample");
        return $this->sampleImageRepository->create([
            "image" => $imagePath
        ]);
    }

    public function removeImage($id)
    {
        $imagePath = $this->sampleImageRepository->findOrFail($id);
        $this->s3Service->remove("sample/" . $imagePath->image);
        return $this->sampleImageRepository->delete($imagePath);
    }

    public function addVideo($id)
    {
        $sampleVideo = $this->sampleVideoRepository->findByVideoId($id);
        if (!$sampleVideo) {
            $sampleVideo = $this->sampleVideoRepository->create([
                "vlog_id" => $id
            ]);
        }
        return $sampleVideo;
    }

    public function deleteVideo($id)
    {
        $sampleVideo = $this->sampleVideoRepository->findOrFail($id);
        return $this->sampleVideoRepository->delete($sampleVideo);
    }

    public function getImages()
    {
        return $this->sampleImageRepository->getAll();
    }

    public function getVideos()
    {
        return $this->sampleVideoRepository->getWithVlog();
    }

    public function sortImage($array)
    {
        foreach ($array as $item) {
            $this->sampleImageRepository->sort($item["id"], $item["sort"]);
        }
        return true;
    }

    public function sortVideo($array)
    {
        foreach ($array as $item) {
            $this->sampleVideoRepository->sort($item["id"], $item["sort"]);
        }
        return true;
    }
}
