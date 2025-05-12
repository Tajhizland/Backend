<?php

namespace App\Http\Resources;

use App\Http\Resources\V1\News\NewsCollection;
use App\Http\Resources\V1\Poster\PosterResource;
use App\Http\Resources\V1\Vlog\VlogCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "poster" => new PosterResource($this["poster"]),
            "blog" => new NewsCollection($this["blog"]),
            "vlog" => new VlogCollection($this["vlog"]),
        ];
    }
}
