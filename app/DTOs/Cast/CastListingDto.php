<?php

namespace App\DTOs\Cast;

class CastListingDto
{
    public function __construct(
        public mixed $filter = null,
    )
    {
    }
}
