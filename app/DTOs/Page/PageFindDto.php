<?php

namespace App\DTOs\Page;

class PageFindDto
{
    public function __construct(
        public string $url,
    )
    {
    }
}
