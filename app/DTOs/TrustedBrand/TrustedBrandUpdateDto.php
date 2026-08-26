<?php

namespace App\DTOs\TrustedBrand;

class TrustedBrandUpdateDto
{
    public function __construct(
        public int   $trustedBrandId,
        public mixed $logo,
    )
    {
    }
}
