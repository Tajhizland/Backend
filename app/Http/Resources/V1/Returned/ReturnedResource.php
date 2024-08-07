<?php

namespace App\Http\Resources\V1\Returned;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Returned */
class ReturnedResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'order_id' => $this->order_id,
            'order_item_id' => $this->order_item_id,
            'count' => $this->count,
            'description' => $this->description,
            'status' => $this->status,
            'file' => $this->file,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
