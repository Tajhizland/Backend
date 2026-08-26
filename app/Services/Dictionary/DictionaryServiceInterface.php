<?php

namespace App\Services\Dictionary;

use App\DTOs\Dictionary\DictionaryStoreDto;
use App\DTOs\Dictionary\DictionaryUpdateDto;

interface DictionaryServiceInterface
{
    public function dataTable(): mixed;

    public function find(int $id): mixed;

    public function store(DictionaryStoreDto $dto): mixed;

    public function update(DictionaryUpdateDto $dto): bool;

    public function delete(int $id): bool|null;

    public function check($original_word): mixed;
}
