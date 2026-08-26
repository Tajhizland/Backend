<?php

namespace App\DTOs\HomepageVlog;

class HomepageVlogUpdateDto
{
    public function __construct(
        public int $homepageVlogId,
        public int $vlogId,
    )
    {
    }
}
