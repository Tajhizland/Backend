<?php

namespace App\Services\Sample;

use App\DTOs\Sample\SampleImageDto;
use App\DTOs\Sample\SampleSortImageDto;
use App\DTOs\Sample\SampleSortVideoDto;
use App\DTOs\Sample\SampleUpdateDto;
use App\DTOs\Sample\SampleVideoDto;

interface SampleServiceInterface
{
    public function find(): mixed;

    public function getImages(): mixed;

    public function getVideos(): mixed;

    public function update(SampleUpdateDto $dto): mixed;

    public function uploadImage(SampleImageDto $dto): mixed;

    public function removeImage(int $id): bool|null;

    public function addVideo(SampleVideoDto $dto): mixed;

    public function deleteVideo(int $id): bool|null;

    public function sortImage(SampleSortImageDto $dto): bool;

    public function sortVideo(SampleSortVideoDto $dto): bool;
}
