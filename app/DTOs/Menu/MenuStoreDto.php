<?php

namespace App\DTOs\Menu;

class MenuStoreDto
{
    public function __construct(
        public string $title,
        public string $url,
        public mixed  $status,
        public mixed  $parent_id = null,
        public mixed  $category_id = null,
        public mixed  $banner_link = null,
        public mixed  $banner_logo = null,
    )
    {
    }
}
