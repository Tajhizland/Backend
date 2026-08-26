<?php

namespace App\Services\HomepageCategory;

use App\DTOs\HomepageCategory\HomepageCategoryAddDto;
use App\DTOs\HomepageCategory\HomepageCategorySetIconDto;

interface HomepageCategoryServiceInterface
{
    public function dataTable(): mixed;

    public function find(int $id): mixed;

    public function add(HomepageCategoryAddDto $dto): mixed;

    public function setIcon(HomepageCategorySetIconDto $dto): bool;

    public function delete(int $id): bool|null;
}
