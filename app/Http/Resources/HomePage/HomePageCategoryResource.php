<?php

namespace App\Http\Resources\HomePage;

use App\Http\Resources\Product\Card\ProductCardResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * یک تب از بخش «دسته بندی های پرطرفدار» صفحه اصلی.
 *
 * ساختار تخت است: آیکون از homepage_categories و بقیه فیلدها از خود دسته‌بندی
 * می‌آید، پس دیگر لازم نیست فرانت با item.category.products کار کند.
 *
 * @mixin \App\Models\HomepageCategory
 */
class HomePageCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->category_id,
            "name" => $this->category?->name,
            "url" => $this->category?->url,
            "image" => $this->category?->image,
            "icon" => $this->icon,
            "products" => ProductCardResource::collection($this->category?->products ?? []),
        ];
    }
}
