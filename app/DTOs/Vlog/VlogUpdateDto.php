<?php

namespace App\DTOs\Vlog;

class VlogUpdateDto
{
    public function __construct(
        public int     $vlogId,
        public string  $title,
        public string  $url,
        public int     $status,
        public int     $categoryId,
        public mixed   $video = null,
        public mixed   $poster = null,
        public ?string $description = null,
    )
    {
    }
}
