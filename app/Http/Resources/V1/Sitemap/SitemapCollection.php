<?php

namespace App\Http\Resources\V1\Sitemap;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SitemapCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
        ];
    }
}
