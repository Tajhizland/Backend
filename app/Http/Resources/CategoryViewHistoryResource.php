<?php

namespace App\Http\Resources;

use App\Http\Resources\V1\User\UserResource;
use App\Models\CategoryViewHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin CategoryViewHistory */
class CategoryViewHistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'user_id' => $this->user_id,
            'category_id' => $this->category_id,

            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
