<?php

namespace App\Services\RunConceptAnswer;

use App\Repositories\RunConceptAnswer\RunConceptAnswerRepositoryInterface;

class RunConceptAnswerService implements RunConceptAnswerServiceInterface
{
    public function __construct
    (
        private RunConceptAnswerRepositoryInterface $runConceptAnswerRepository
    )
    {
    }

    public function dataTable()
    {
        return $this->runConceptAnswerRepository->dataTable();
    }

    public function find($id)
    {
        return $this->runConceptAnswerRepository->findOrFail($id);
    }

    public function getByQuestionId($id)
    {
        return $this->runConceptAnswerRepository->getByQuestionId($id);
    }

    public function store($run_concept_question_id, $answer, $status, $price)
    {
        return $this->runConceptAnswerRepository->create(
            [
                "run_concept_question_id" => $run_concept_question_id,
                "answer" => $answer,
                "status" => $status,
                "price" => $price,
            ]);
    }

    public function update($id, $run_concept_question_id, $answer, $status, $price)
    {
        $model = $this->runConceptAnswerRepository->findOrFail($id);
        return $this->runConceptAnswerRepository->update($model,
            [
                "run_concept_question_id" => $run_concept_question_id,
                "answer" => $answer,
                "status" => $status,
                "price" => $price,
            ]);
    }
}
