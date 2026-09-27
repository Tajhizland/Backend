<?php

namespace App\DTOs\InstagramPost;

class InstagramPostMediaDto
{
    public function __construct(
        public int   $postId,
        /** @var \Illuminate\Http\UploadedFile[] */
        public array $media,
    )
    {
    }
}
