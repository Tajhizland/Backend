<?php

namespace App\Http\Resources\V1\LandingBanner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LandingBannerCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
        ];
    }
}
