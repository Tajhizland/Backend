<?php

namespace App\DTOs\News;

class NewsListingDto
{
    public function __construct(
        public mixed $filter = null,
    )
    {
    }
}
