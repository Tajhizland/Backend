<?php

namespace App\DTOs\HomepageCategory;

class HomepageCategorySetIconDto
{
    public function __construct(
        public int   $homepageCategoryId,
        public mixed $icon,
    )
    {
    }
}
