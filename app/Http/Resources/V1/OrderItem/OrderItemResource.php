<?php

namespace App\Http\Resources\V1\OrderItem;

use App\Http\Resources\V1\Order\OrderResource;
use App\Http\Resources\V1\Product\ProductResource;
use App\Http\Resources\V1\ProductColor\ProductColorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\OrderItem */
class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'count' => $this->count,
            'price' => $this->price,
            'dicount' => $this->dicount,
            'final_price' => $this->final_price,
            'unit_price' => $this->unit_price,
            'unit_discount' => $this->unit_discount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'product_id' => $this->product_id,
            'product_color_id' => $this->product_color_id,
            'order_id' => $this->order_id,

            'order' => new OrderResource($this->whenLoaded('order')),
            'product' => new ProductResource($this->whenLoaded('product')),
            'productColor' => new ProductColorResource($this->whenLoaded('productColor')),
        ];
    }
}
