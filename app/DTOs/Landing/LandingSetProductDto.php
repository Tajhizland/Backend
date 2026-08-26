<?php

namespace App\DTOs\Landing;

class LandingSetProductDto
{
    public function __construct(
        public int $landing_id,
        public int $product_id,
    )
    {
    }
}
