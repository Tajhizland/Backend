<?php

namespace App\DTOs\RunConceptAnswer;

class RunConceptAnswerStoreDto
{
    public function __construct(
        public int    $run_concept_question_id,
        public string $answer,
        public int    $status,
        public int    $price,
    )
    {
    }
}
