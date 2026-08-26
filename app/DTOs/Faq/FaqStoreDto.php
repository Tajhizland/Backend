<?php

namespace App\DTOs\Faq;

class FaqStoreDto
{
    public function __construct(
        public string $question,
        public string $answer,
        public int    $status,
    )
    {
    }
}
