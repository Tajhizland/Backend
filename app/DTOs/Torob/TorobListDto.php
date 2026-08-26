<?php

namespace App\DTOs\Torob;

class TorobListDto
{
    public function __construct(
        public mixed $page_urls = null,
        public mixed $page_uniques = null,
        public mixed $page = null,
        public mixed $sort = null,
    )
    {
    }
}
