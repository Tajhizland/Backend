<?php

namespace App\Http\Resources\Cast;

use App\Http\Resources\CastCategory\CastCategoryResource;
use App\Http\Resources\Vlog\VlogResource;
use App\Models\Cast;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Cast */
class CastResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'audio' => $this->audio,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'url' => $this->url,
            'status' => $this->status,
            'category_id' => $this->category_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'vlog_id' => $this->vlog_id,

            'vlog' => new VlogResource($this->whenLoaded('vlog')),
            'castCategory' => new CastCategoryResource($this->whenLoaded('castCategory')),
        ];
    }
}
