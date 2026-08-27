<?php

namespace App\Http\Resources\Product\Card;

use App\Http\Resources\Guaranty\GuarantyCardResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * نسخه سبک ProductResource برای کارت محصول (اسلایدرها و گریدهای فروشگاه).
 *
 * فقط فیلدهایی که کارت‌های فرانت (ProductCard2 / ProductCard3 / CollectionProductCard)
 * مصرف می‌کنند. برای استفاده حتما با Product::forCard() کوئری بزنید.
 *
 * @mixin \App\Models\Product
 */
class ProductCardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "url" => $this->url,
            "description" => $this->description,
            "type" => $this->type,
            "is_stock" => $this->is_stock,
            "min_price" => $this->numeric($this->min_price),
            "rating" => $this->numeric($this->rating),
            "comments_count" => (int)($this->comments_count ?? 0),
            "guaranty" => new GuarantyCardResource($this->whenLoaded("guaranty")),
            "images" => ProductImageCardResource::collection($this->whenLoaded("images")),
            "colors" => ProductColorCardResource::collection($this->whenLoaded("activeProductColors")),
        ];
    }

    /**
     * مقادیر aggregate در mysql به صورت string برمی‌گردند؛ در خروجی json عدد می‌خواهیم.
     */
    private function numeric(mixed $value): int|float|null
    {
        return is_numeric($value) ? $value + 0 : null;
    }
}
