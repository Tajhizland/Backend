<?php

namespace App\Services\RunConceptAnswer;

use App\DTOs\RunConceptAnswer\RunConceptAnswerStoreDto;
use App\DTOs\RunConceptAnswer\RunConceptAnswerUpdateDto;
use App\Repositories\RunConceptAnswer\RunConceptAnswerRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class RunConceptAnswerService implements RunConceptAnswerServiceInterface
{
    public function __construct
    (
        private RunConceptAnswerRepositoryInterface $runConceptAnswerRepository
    )
    {
    }

    public function dataTable(): mixed
    {
        return $this->runConceptAnswerRepository->dataTable();
    }

    public function find(int $id): mixed
    {
        $answer = $this->runConceptAnswerRepository->find($id);
        if (!$answer) {
            throw new NotFoundHttpException();
        }
        return $answer;
    }

    public function getByQuestionId($id): mixed
    {
        return $this->runConceptAnswerRepository->getByQuestionId($id);
    }

    public function store(RunConceptAnswerStoreDto $dto): mixed
    {
        return $this->runConceptAnswerRepository->create([
            "run_concept_question_id" => $dto->run_concept_question_id,
            "answer" => $dto->answer,
            "status" => $dto->status,
            "price" => $dto->price,
        ]);
    }

    public function update(RunConceptAnswerUpdateDto $dto): bool
    {
        $answer = $this->find($dto->runConceptAnswerId);
        return $this->runConceptAnswerRepository->update($answer, [
            "run_concept_question_id" => $dto->run_concept_question_id,
            "answer" => $dto->answer,
            "status" => $dto->status,
            "price" => $dto->price,
        ]);
    }
}
