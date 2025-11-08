<?php

namespace App\Http\Resources;

use App\Http\Resources\V1\Vlog\VlogResource;
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
            'image' => $this->image,
            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->url,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'vlog_id' => $this->vlog_id,

            'vlog' => new VlogResource($this->whenLoaded('vlog')),
        ];
    }
}
