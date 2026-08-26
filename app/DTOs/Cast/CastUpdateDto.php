<?php

namespace App\DTOs\Cast;

class CastUpdateDto
{
    public function __construct(
        public int     $castId,
        public string  $title,
        public string  $url,
        public int     $status,
        public int     $vlog_id,
        public int     $category_id,
        public mixed   $audio = null,
        public mixed   $image = null,
        public ?string $description = null,
    )
    {
    }
}
