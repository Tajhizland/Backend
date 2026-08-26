<?php

namespace App\DTOs\Concept;

class ConceptUpdateDto
{
    public function __construct(
        public int     $conceptId,
        public string  $title,
        public int     $status,
        public ?string $description = null,
        public mixed   $icon = null,
    )
    {
    }
}
