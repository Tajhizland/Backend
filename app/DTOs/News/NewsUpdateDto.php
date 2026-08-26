<?php

namespace App\DTOs\News;

class NewsUpdateDto
{
    public function __construct(
        public int    $newsId,
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
