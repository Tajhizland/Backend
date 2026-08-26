<?php

namespace App\DTOs\RunConceptAnswer;

class RunConceptAnswerUpdateDto
{
    public function __construct(
        public int    $runConceptAnswerId,
        public int    $run_concept_question_id,
        public string $answer,
        public int    $status,
        public int    $price,
    )
    {
    }
}
