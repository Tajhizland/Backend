<?php

namespace App\DTOs\Vlog;

class VlogSearchDto
{
    public function __construct(
        public string $query,
    )
    {
    }
}
