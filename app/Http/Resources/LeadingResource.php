<?php

namespace App\Http\Resources;

use App\Http\Resources\Poster\PosterResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\News\NewsResource;
use App\Http\Resources\Vlog\VlogResource;

class LeadingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "poster" => new PosterResource($this["poster"]),
            "blog" => NewsResource::collection($this["blog"]),
            "vlog" => VlogResource::collection($this["vlog"]),
        ];
    }
}
