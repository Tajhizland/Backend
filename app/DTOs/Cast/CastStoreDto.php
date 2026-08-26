<?php

namespace App\DTOs\Cast;

class CastStoreDto
{
    public function __construct(
        public string  $title,
        public string  $url,
        public int     $status,
        public int     $vlog_id,
        public int     $category_id,
        public mixed   $audio,
        public mixed   $image,
        public ?string $description = null,
    )
    {
    }
}
