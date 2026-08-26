<?php

namespace App\Services\Slider;

use App\DTOs\Slider\SliderSortDto;
use App\DTOs\Slider\SliderStoreDto;
use App\DTOs\Slider\SliderUpdateDto;
use App\Repositories\Slider\SliderRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\S3\S3ServiceInterface;

readonly class SliderService implements SliderServiceInterface
{
    public function __construct
    (
        private SliderRepositoryInterface $sliderRepository,
        private S3ServiceInterface        $s3Service,
    )
    {
    }

    public function find(int $id): mixed
    {
        $model = $this->sliderRepository->find($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        return $model;
    }

    public function dataTable(): mixed
    {
        return $this->sliderRepository->dataTable();
    }

    public function store(SliderStoreDto $dto): mixed
    {
        return $this->sliderRepository->create([
            "title" => $dto->title,
            "url" => $dto->url,
            "image" => $this->s3Service->upload($dto->image, "slider"),
            "type" => $dto->type,
            "status" => $dto->status,
        ]);
    }

    public function update(SliderUpdateDto $dto): bool
    {
        $slider = $this->find($dto->sliderId);
        $imagePath = $slider->image;
        if ($dto->image) {
            $this->s3Service->remove("slider/" . $slider->image);
            $imagePath = $this->s3Service->upload($dto->image, "slider");
        }
        return $this->sliderRepository->update($slider, [
            "title" => $dto->title,
            "url" => $dto->url,
            "image" => $imagePath,
            "status" => $dto->status,
            "type" => $dto->type,
        ]);
    }

    public function getAllDesktop(): mixed
    {
        return $this->sliderRepository->getAllDesktop();
    }

    public function getAllMobile(): mixed
    {
        return $this->sliderRepository->getAllMobile();
    }

    public function sort(SliderSortDto $dto): bool
    {
        foreach ($dto->slider as $item) {
            $this->sliderRepository->sort($item["id"], $item["sort"]);
        }
        return true;
    }

    public function delete(int $id): bool|null
    {
        return $this->sliderRepository->delete($this->find($id));
    }
}
