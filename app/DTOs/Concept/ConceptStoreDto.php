<?php

namespace App\DTOs\Concept;

class ConceptStoreDto
{
    public function __construct(
        public string  $title,
        public int     $status,
        public ?string $description = null,
        public mixed   $icon = null,
    )
    {
    }
}
