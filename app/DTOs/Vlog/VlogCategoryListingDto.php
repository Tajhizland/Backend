<?php

namespace App\DTOs\Vlog;

class VlogCategoryListingDto
{
    public function __construct(
        public string $url,
        public mixed $filter = null,
    )
    {
    }
}
