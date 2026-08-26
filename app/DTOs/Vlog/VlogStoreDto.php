<?php

namespace App\DTOs\Vlog;

class VlogStoreDto
{
    public function __construct(
        public int     $author,
        public string  $title,
        public string  $url,
        public int     $status,
        public int     $categoryId,
        public mixed   $video,
        public mixed   $poster,
        public ?string $description = null,
    )
    {
    }
}
