<?php

namespace App\DTOs\Poster;

class PosterStoreDto
{
    public function __construct(
        public mixed $image,
    )
    {
    }
}
