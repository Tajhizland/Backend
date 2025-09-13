<?php

namespace App\Http\Resources\V1\Dictionary;

use App\Models\Dictionary;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Dictionary */
class DictionaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'original_word' => $this->original_word,
            'mean' => $this->mean,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
