<?php

namespace App\Services\RunConceptQuestion;

interface RunConceptQuestionServiceInterface
{
    public function dataTable();
    public function list();
    public function find($id);

    public function store($question, $status, $level, $parent_question, $parent_answer);
    public function update($id,$question, $status, $level, $parent_question, $parent_answer);
}
