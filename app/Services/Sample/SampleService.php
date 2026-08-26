<?php

namespace App\Services\Sample;

use App\DTOs\Sample\SampleImageDto;
use App\DTOs\Sample\SampleSortImageDto;
use App\DTOs\Sample\SampleSortVideoDto;
use App\DTOs\Sample\SampleUpdateDto;
use App\DTOs\Sample\SampleVideoDto;
use App\Repositories\Sample\SampleRepositoryInterface;
use App\Repositories\SampleImage\SampleImageRepositoryInterface;
use App\Repositories\SampleVideo\SampleVideoRepositoryInterface;
use App\Services\S3\S3ServiceInterface;

readonly class SampleService implements SampleServiceInterface
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

    public function find(): mixed
    {
        return $this->sampleRepository->first();
    }

    public function update(SampleUpdateDto $dto): mixed
    {
        $sample = $this->sampleRepository->first();
        return $this->sampleRepository->update($sample, [
            "content" => $dto->content,
        ]);
    }

    public function uploadImage(SampleImageDto $dto): mixed
    {
        $imagePath = $this->s3Service->upload($dto->image, "sample");
        return $this->sampleImageRepository->create([
            "image" => $imagePath
        ]);
    }

    public function removeImage(int $id): bool|null
    {
        $imagePath = $this->sampleImageRepository->findOrFail($id);
        $this->s3Service->remove("sample/" . $imagePath->image);
        return $this->sampleImageRepository->delete($imagePath);
    }

    public function addVideo(SampleVideoDto $dto): mixed
    {
        $sampleVideo = $this->sampleVideoRepository->findByVideoId($dto->vlog_id);
        if (!$sampleVideo) {
            $sampleVideo = $this->sampleVideoRepository->create([
                "vlog_id" => $dto->vlog_id,
            ]);
        }
        return $sampleVideo;
    }

    public function deleteVideo(int $id): bool|null
    {
        $sampleVideo = $this->sampleVideoRepository->findOrFail($id);
        return $this->sampleVideoRepository->delete($sampleVideo);
    }

    public function getImages(): mixed
    {
        return $this->sampleImageRepository->getAll();
    }

    public function getVideos(): mixed
    {
        return $this->sampleVideoRepository->getWithVlog();
    }

    public function sortImage(SampleSortImageDto $dto): bool
    {
        foreach ($dto->image as $item) {
            $this->sampleImageRepository->sort($item["id"], $item["sort"]);
        }
        return true;
    }

    public function sortVideo(SampleSortVideoDto $dto): bool
    {
        foreach ($dto->video as $item) {
            $this->sampleVideoRepository->sort($item["id"], $item["sort"]);
        }
        return true;
    }
}
