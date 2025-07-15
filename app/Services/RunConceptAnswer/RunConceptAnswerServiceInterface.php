<?php

namespace App\Services\RunConceptAnswer;

interface RunConceptAnswerServiceInterface
{
    public function dataTable();

    public function find($id);
    public function getByQuestionId($id);

    public function store($run_concept_question_id, $answer, $status, $price);

    public function update($id, $run_concept_question_id, $answer, $status, $price);
}
