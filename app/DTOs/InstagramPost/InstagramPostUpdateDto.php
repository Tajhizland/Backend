<?php

namespace App\DTOs\InstagramPost;

class InstagramPostUpdateDto
{
    public function __construct(
        public int     $postId,
        public int     $status,
        public ?string $caption = null,
    )
    {
    }
}
