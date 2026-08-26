<?php

namespace App\Services\RunConceptQuestion;

use App\DTOs\RunConceptQuestion\RunConceptQuestionStoreDto;
use App\DTOs\RunConceptQuestion\RunConceptQuestionUpdateDto;

interface RunConceptQuestionServiceInterface
{
    public function dataTable(): mixed;

    public function list(): mixed;

    public function find(int $id): mixed;

    public function store(RunConceptQuestionStoreDto $dto): mixed;

    public function update(RunConceptQuestionUpdateDto $dto): bool;
}
