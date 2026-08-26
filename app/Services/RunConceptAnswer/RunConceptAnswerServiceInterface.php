<?php

namespace App\Services\RunConceptAnswer;

use App\DTOs\RunConceptAnswer\RunConceptAnswerStoreDto;
use App\DTOs\RunConceptAnswer\RunConceptAnswerUpdateDto;

interface RunConceptAnswerServiceInterface
{
    public function dataTable(): mixed;

    public function getByQuestionId($id): mixed;

    public function find(int $id): mixed;

    public function store(RunConceptAnswerStoreDto $dto): mixed;

    public function update(RunConceptAnswerUpdateDto $dto): bool;
}
