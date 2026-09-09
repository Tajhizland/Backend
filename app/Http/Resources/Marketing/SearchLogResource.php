<?php

namespace App\Http\Resources\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/** @mixin \App\Models\SearchLog */
class SearchLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'term' => $this->term,
            'result_count' => $this->result_count,
            'product_count' => $this->product_count,
            'ip' => $this->ip,
            'source' => $this->source,
            'user' => $this->whenLoaded('user', fn() => [
                'id' => $this->user?->id,
                'name' => trim(($this->user?->name ?? '') . ' ' . ($this->user?->last_name ?? '')),
                'username' => $this->user?->username,
            ]),
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
        ];
    }
}
