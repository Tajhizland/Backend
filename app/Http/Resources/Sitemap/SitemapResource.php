<?php

namespace App\Http\Resources\Sitemap;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SitemapResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "url" => $this->url,
        ];
    }
}
