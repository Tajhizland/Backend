<?php

namespace App\Http\Resources\V1\Comment;

use App\Http\Resources\V1\Product\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/** @mixin \App\Models\Comment */
class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user->name,
            'product_id' => $this->product_id,
            'product' => new ProductResource($this->whenLoaded($this->product)),
            'rating' => $this->rating,
            'text' => $this->text,
            'status' => $this->status->label(),
             'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),
        ];
    }
}
