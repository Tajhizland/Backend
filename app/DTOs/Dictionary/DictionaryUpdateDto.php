<?php

namespace App\DTOs\Dictionary;

class DictionaryUpdateDto
{
    public function __construct(
        public int    $dictionaryId,
        public string $original_word,
        public string $mean,
    )
    {
    }
}
