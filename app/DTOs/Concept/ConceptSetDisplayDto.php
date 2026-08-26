<?php

namespace App\DTOs\Concept;

class ConceptSetDisplayDto
{
    public function __construct(
        public int    $categoryConceptId,
        public string $display,
    )
    {
    }
}
