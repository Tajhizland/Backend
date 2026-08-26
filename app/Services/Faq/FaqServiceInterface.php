<?php

namespace App\Services\Faq;

use App\DTOs\Faq\FaqStoreDto;
use App\DTOs\Faq\FaqUpdateDto;

interface FaqServiceInterface
{
    public function dataTable(): mixed;

    public function getActive(): mixed;

    public function find(int $id): mixed;

    public function store(FaqStoreDto $dto): mixed;

    public function update(FaqUpdateDto $dto): bool;
}
