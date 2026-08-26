<?php

namespace App\DTOs\Vlog;

class VlogListingDto
{
    public function __construct(
        public mixed $filter = null,
    )
    {
    }
}
