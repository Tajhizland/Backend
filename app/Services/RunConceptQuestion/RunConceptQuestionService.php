<?php

namespace App\Services\RunConceptQuestion;

use App\DTOs\RunConceptQuestion\RunConceptQuestionStoreDto;
use App\DTOs\RunConceptQuestion\RunConceptQuestionUpdateDto;
use App\Repositories\RunConceptQuestion\RunConceptQuestionRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class RunConceptQuestionService implements RunConceptQuestionServiceInterface
{
    public function __construct
    (
        private RunConceptQuestionRepositoryInterface $conceptQuestionRepository
    )
    {
    }

    public function dataTable(): mixed
    {
        return $this->conceptQuestionRepository->dataTable();
    }

    public function store(RunConceptQuestionStoreDto $dto): mixed
    {
        return $this->conceptQuestionRepository->create([
            "question" => $dto->question,
            "status" => $dto->status,
            "level" => $dto->level,
            "parent_question" => $dto->parent_question,
            "parent_answer" => $dto->parent_answer,
        ]);
    }

    public function find(int $id): mixed
    {
        $question = $this->conceptQuestionRepository->find($id);
        if (!$question) {
            throw new NotFoundHttpException();
        }
        return $question;
    }

    public function update(RunConceptQuestionUpdateDto $dto): bool
    {
        $question = $this->find($dto->runConceptQuestionId);
        return $this->conceptQuestionRepository->update($question, [
            "question" => $dto->question,
            "status" => $dto->status,
            "level" => $dto->level,
            "parent_question" => $dto->parent_question,
            "parent_answer" => $dto->parent_answer,
        ]);
    }

    public function list(): mixed
    {
        return $this->conceptQuestionRepository->list();
    }
}
