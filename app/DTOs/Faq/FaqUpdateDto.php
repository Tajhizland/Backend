<?php

namespace App\DTOs\Faq;

class FaqUpdateDto
{
    public function __construct(
        public int    $faqId,
        public string $question,
        public string $answer,
        public int    $status,
    )
    {
    }
}
