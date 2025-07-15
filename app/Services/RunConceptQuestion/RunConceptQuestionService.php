<?php

namespace App\Services\RunConceptQuestion;

use App\Repositories\RunConceptQuestion\RunConceptQuestionRepositoryInterface;

class RunConceptQuestionService implements RunConceptQuestionServiceInterface
{
    public function __construct
    (
        private RunConceptQuestionRepositoryInterface $conceptQuestionRepository
    )
    {
    }

    public function dataTable()
    {
        return $this->conceptQuestionRepository->dataTable();
    }

    public function store($question, $status, $level, $parent_question, $parent_answer)
    {
        return $this->conceptQuestionRepository->create([
            "question" => $question,
            "status" => $status,
            "level" => $level,
            "parent_question" => $parent_question,
            "parent_answer" => $parent_answer
        ]);
    }

    public function find($id)
    {
        return $this->conceptQuestionRepository->findOrFail($id);
    }

    public function update($id, $question, $status, $level, $parent_question, $parent_answer)
    {
        $model = $this->conceptQuestionRepository->findOrFail($id);
        $this->conceptQuestionRepository->update($model, [
            "question" => $question,
            "status" => $status,
            "level" => $level,
            "parent_question" => $parent_question,
            "parent_answer" => $parent_answer
        ]);
    }
}
