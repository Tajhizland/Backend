<?php

namespace App\DTOs\Concept;

class ConceptSetItemDto
{
    public function __construct(
        public int $concept_id,
        public int $category_id,
    )
    {
    }
}
