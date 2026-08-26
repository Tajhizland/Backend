<?php

namespace App\DTOs\Sample;

class SampleUpdateDto
{
    public function __construct(
        public mixed $content,
    )
    {
    }
}
