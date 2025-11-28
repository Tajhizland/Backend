<?php

namespace App\Http\Resources\V1\ProductColor;

use App\Http\Resources\V1\DiscountItem\DiscountItemCollection;
use App\Http\Resources\V1\Product\SimpleProduct\SimpleProductResource;
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
        } else if ($this->created_at > Carbon::now()->subWeek()) {
            $statusLabel = "new";
        }

//        $discountedPrice = $this->price?->price;
//        if ($this->price->discount_expire_time == null) {
//            $discountedPrice = $this->price?->discount != 0 ? $this->price?->discount : $this->price?->price;
//        } else {
//            if ($this->price->discount_expire_time > Carbon::now()) {
//                $discountedPrice = $this->price?->discount != 0 ? $this->price?->discount : $this->price?->price;
//            }
//        }
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'color_name' => $this->color_name,
            'color_code' => $this->color_code,
            'delivery_delay' => $this->delivery_delay,
            'status' => $this->status,
            'statusLabel' => $statusLabel,
            'price' => $this->price?->price,
//            'discount_expire_time' => $this->price?->discount_expire_time,
//            'discount_expire_time_fa' => $this->price?->discount_expire_time != null ? Jalalian::fromDateTime($this->price?->discount_expire_time)->format('Y/m/d') : "",
//             'discount' => round(($this->price?->price - $this->price?->discount) / ($this->price?->price != 0 ? $this->price?->price : 1) * 100),
//            'discountedPrice' => $discountedPrice,
            'product' => new SimpleProductResource($this->whenLoaded('product')),
            'discountItem' => new DiscountItemCollection($this->whenLoaded('discountItem')),

//            'discountedPrice' => $this->price?->discount != 0 ? $this->price?->discount : $this->price?->price,
            'stock' => $this->stock?->stock ?? 0,
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),
        ];
    }
}
