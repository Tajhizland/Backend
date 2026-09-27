<?php

namespace App\DTOs\InstagramPost;

class InstagramPostFindDto
{
    public function __construct(
        public string $url,
    )
    {
    }
}
