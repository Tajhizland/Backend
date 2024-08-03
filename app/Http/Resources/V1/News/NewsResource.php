<?php

namespace App\Http\Resources\V1\News;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\News */
class NewsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "url" => $this->url,
            "content" => $this->content,
            "img" => $this->img,
            "published" => $this->published,
            "static" => $this->static,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
