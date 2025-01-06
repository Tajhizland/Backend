<?php

namespace App\Http\Resources\V1\OrderItem;

use App\Http\Resources\V1\Guaranty\GuarantyResource;
use App\Http\Resources\V1\Order\OrderResource;
use App\Http\Resources\V1\Product\ProductResource;
use App\Http\Resources\V1\ProductColor\ProductColorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/** @mixin \App\Models\OrderItem */
class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'count' => $this->count,
            'price' => $this->price,
            'final_price' => $this->final_price,
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),

            'product_id' => $this->product_id,
            'product_color_id' => $this->product_color_id,
            'order_id' => $this->order_id,
            'guaranty_id' => $this->guaranty_id,
            'guaranty_price' => $this->guaranty_price,

            'guaranty' => new GuarantyResource($this->whenLoaded('guaranty')),
            'order' => new OrderResource($this->whenLoaded('order')),
            'product' => new ProductResource($this->whenLoaded('product')),
            'productColor' => new ProductColorResource($this->whenLoaded('productColor')),
        ];
    }
}
