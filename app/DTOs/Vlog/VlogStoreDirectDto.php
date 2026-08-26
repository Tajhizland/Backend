<?php

namespace App\DTOs\Vlog;

class VlogStoreDirectDto
{
    public function __construct(
        public int     $author,
        public string  $title,
        public string  $url,
        public int     $status,
        public int     $categoryId,
        public string  $videoKey,
        public mixed   $poster,
        public ?string $description = null,
    )
    {
    }
}
