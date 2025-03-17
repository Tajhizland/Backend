<?php

namespace App\Http\Resources\V1\SampleVideo;

use App\Http\Resources\V1\Vlog\VlogResource;
use App\Models\SampleVideo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin SampleVideo */
class SampleVideoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sort' => $this->sort,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'vlog_id' => $this->vlog_id,

            'vlog' => new VlogResource($this->whenLoaded('vlog')),
        ];
    }
}
