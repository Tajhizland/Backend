<?php

namespace App\Services\Slider;

use App\DTOs\Slider\SliderSortDto;
use App\DTOs\Slider\SliderStoreDto;
use App\DTOs\Slider\SliderUpdateDto;

interface SliderServiceInterface
{
    public function dataTable(): mixed;

    public function getAllDesktop(): mixed;

    public function getAllMobile(): mixed;

    public function find(int $id): mixed;

    public function store(SliderStoreDto $dto): mixed;

    public function update(SliderUpdateDto $dto): bool;

    public function sort(SliderSortDto $dto): bool;

    public function delete(int $id): bool|null;
}
