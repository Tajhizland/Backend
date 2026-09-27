<?php

namespace App\DTOs\InstagramPost;

class InstagramPostListingDto
{
    public function __construct(
        public mixed $filter = null,
    )
    {
    }
}
