<?php

namespace App\Http\Resources\V1\HomepageVlog;

use App\Http\Resources\V1\Vlog\VlogResource;
use App\Models\HomepageVlog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin HomepageVlog */
class HomepageVlogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'vlog_id' => $this->vlog_id,

            'vlog' => new VlogResource($this->whenLoaded('vlog')),
        ];
    }
}
