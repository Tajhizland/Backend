<?php

namespace App\Http\Resources\V1\Comment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Comment */
class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user->name,
            'product_id' => $this->product_id,
            'rating' => $this->rating,
            'text' => $this->text,
            'status' => $this->status->label(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
