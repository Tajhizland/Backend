<?php

namespace App\DTOs\InstagramPost;

class InstagramPostStoreDto
{
    public function __construct(
        public int     $author,
        public int     $status,
        /** @var \Illuminate\Http\UploadedFile[] */
        public array   $media,
        public ?string $caption = null,
        /** @var array<int, \Illuminate\Http\UploadedFile|null> کاور با همان اندیس ویدیو */
        public array   $covers = [],
    )
    {
    }
}
