<?php

namespace App\DTOs\Page;

class PageUpdateDto
{
    public function __construct(
        public int    $pageId,
        public string $title,
        public string $url,
        public string $content,
        public int    $status,
        public mixed  $image = null,
    )
    {
    }
}
