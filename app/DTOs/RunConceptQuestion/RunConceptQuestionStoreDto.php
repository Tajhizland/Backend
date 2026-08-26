<?php

namespace App\DTOs\RunConceptQuestion;

class RunConceptQuestionStoreDto
{
    public function __construct(
        public string $question,
        public int    $status,
        public int    $level,
        public mixed  $parent_question = null,
        public mixed  $parent_answer = null,
    )
    {
    }
}
