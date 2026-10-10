<?php

namespace App\Http\Resources\Sitemap;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SitemapResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Include catalog changes as well as edits to the product record itself.
        $modified = collect($this->resource->getAttributes())
            ->filter(fn ($value, $key) => $value && ($key === 'updated_at' || str_ends_with($key, '_max_updated_at')))
            ->map(fn ($value) => Carbon::parse($value))
            ->sortDesc()->first();

        return [
            "url" => $this->url,
            "updated_at" => $modified?->toISOString(),
        ];
    }
}
