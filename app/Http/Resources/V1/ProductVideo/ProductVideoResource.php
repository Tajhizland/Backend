<?php

namespace App\Http\Resources\V1\ProductVideo;

use App\Http\Resources\V1\Product\ProductResource;
use App\Http\Resources\V1\Vlog\VlogResource;
use App\Models\ProductVideo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ProductVideo */
class ProductVideoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'product_id' => $this->product_id,
            'vlog_id' => $this->vlog_id,

            'product' => new ProductResource($this->whenLoaded('product')),
            'vlog' => new VlogResource($this->vlog),
        ];
    }
}
