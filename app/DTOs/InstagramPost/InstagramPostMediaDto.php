<?php

namespace App\DTOs\InstagramPost;

class InstagramPostMediaDto
{
    public function __construct(
        public int   $postId,
        /** @var \Illuminate\Http\UploadedFile[] */
        public array $media,
        /** @var array<int, \Illuminate\Http\UploadedFile|null> */
        public array $covers = [],
    )
    {
    }
}
