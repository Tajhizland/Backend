<?php

namespace App\Services\Vlog;

use App\DTOs\Vlog\VlogSortDto;
use App\DTOs\Vlog\VlogStoreDirectDto;
use App\DTOs\Vlog\VlogStoreDto;
use App\DTOs\Vlog\VlogUpdateDto;
use App\Models\Vlog;

interface VlogServiceInterface
{
    public function dataTable(): mixed;

    public function listing($filters): mixed;

    public function search($query): mixed;

    public function getMostViewed(): mixed;

    public function find(int $id): mixed;

    public function getRelatedVlogs($category_id, $except): mixed;

    public function findByUrl($url): mixed;

    public function getByCategoryUrl($url, $filters): mixed;

    public function view(Vlog $vlog): mixed;

    public function store(VlogStoreDto $dto): mixed;

    public function storeDirect(VlogStoreDirectDto $dto): mixed;

    public function update(VlogUpdateDto $dto): mixed;

    public function getSitemapData(): mixed;

    public function list(): mixed;

    public function sort(VlogSortDto $dto): bool;
}
