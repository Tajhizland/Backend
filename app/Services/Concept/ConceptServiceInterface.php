<?php

namespace App\Services\Concept;

use App\DTOs\Concept\ConceptSetDisplayDto;
use App\DTOs\Concept\ConceptSetItemDto;
use App\DTOs\Concept\ConceptStoreDto;
use App\DTOs\Concept\ConceptUpdateDto;

interface ConceptServiceInterface
{
    public function dataTable(): mixed;

    public function find(int $id): mixed;

    public function getItemsById($id): mixed;

    public function store(ConceptStoreDto $dto): mixed;

    public function update(ConceptUpdateDto $dto): bool;

    public function setItem(ConceptSetItemDto $dto): mixed;

    public function deleteItem(int $id): bool|null;

    public function setDisplay(ConceptSetDisplayDto $dto): bool;
}
