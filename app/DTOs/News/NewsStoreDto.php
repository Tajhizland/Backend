<?php

namespace App\DTOs\News;

class NewsStoreDto
{
    public function __construct(
        public int    $author,
        public string $title,
        public string $url,
        public string $content,
        public int    $published,
        public mixed  $image = null,
        public mixed  $categoryId = null,
        public mixed  $static = null,
    )
    {
    }
}
