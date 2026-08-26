<?php

namespace App\DTOs\Dictionary;

class DictionaryStoreDto
{
    public function __construct(
        public string $original_word,
        public string $mean,
    )
    {
    }
}
