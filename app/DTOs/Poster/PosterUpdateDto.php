<?php

namespace App\DTOs\Poster;

class PosterUpdateDto
{
    public function __construct(
        public int   $posterId,
        public mixed $image,
    )
    {
    }
}
