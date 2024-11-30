<?php

namespace App\Http\Resources\V1\ProductColor;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/** @mixin \App\Models\ProductColor */
class ProductColorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $statusLabel = "";
        if (!$this->stock || $this->stock->stock < 1 || !$this->price || $this->price->price <= 0) {
            $statusLabel = "disable";
        } else if ($this->price->discount > 0) {
            $statusLabel = "discount";
        } else if ($this->created_at > Carbon::now()->subWeek()) {
            $statusLabel = "new";
        }
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'color_name' => $this->color_name,
            'color_code' => $this->color_code,
            'delivery_delay' => $this->delivery_delay,
            'status' => $this->status,
            'statusLabel' => $statusLabel,
            'price' => $this->price?->price,
            'discount' => $this->price?->discount,
            'discountedPrice' => ($this->price?->price - $this->price?->discount) / ($this->price?->price) * 100,
            'stock' => $this->stock?->stock ?? 0,
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),
        ];
    }
}
