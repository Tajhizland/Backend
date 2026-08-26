<?php

namespace App\DTOs\Page;

class PageStoreDto
{
    public function __construct(
        public string $title,
        public string $url,
        public string $content,
        public int    $status,
        public mixed  $image = null,
    )
    {
    }
}
