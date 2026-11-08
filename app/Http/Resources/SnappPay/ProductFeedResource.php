<?php

namespace App\Http\Resources\SnappPay;

use App\Enums\ProductColorStatus;
use App\Models\Product;
use App\Services\SnappPay\SnappPayPrice;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Product */
class ProductFeedResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $percent = (float) ($this->snappay_extra_price ?? 0);
        $variants = $this->activeProductColors->filter(fn ($color) => (int) $color->price?->price > 0)
            ->map(function ($color) use ($percent) {
                $regular = (int) $color->price->price;

                return [
                    'color' => $color,
                    // Limit colors require admin approval; they are not immediately purchasable.
                    'available' => (int) $color->status === ProductColorStatus::Active->value
                        && (int) $color->stock?->stock > 0,
                    'regular' => $regular + SnappPayPrice::fee($regular, $percent),
                    'sale' => $regular + SnappPayPrice::fee($regular, $percent),
                ];
            });
        $available = $variants->where('available', true);
        // Price and availability must describe the same color, not two different variants.
        $selected = ($available->isNotEmpty() ? $available : $variants)->sortBy('sale')->first();

        $attributes = [];
        foreach ($this->productOptions as $option) {
            $title = $this->plainText($option->optionItem?->title);
            $value = $this->plainText($option->value);
            if ($title !== '' && $value !== '') {
                $attributes[$title] = $value;
            }
        }
        $summary = $this->plainText($this->description);
        if ($summary !== '') {
            $attributes['توضیحات'] = $summary;
        }
        $parentIds = $this->categories->pluck('parent_id')->filter()->all();
        $category = $this->categories->reject(fn ($item) => in_array($item->id, $parentIds))
            ->sortByDesc(fn ($item) => $item->parent_id ? 1 : 0)->first();
        $imageBase = rtrim(config('settings.image_base_url'), '/').'/product/';

        return [
            'id' => (string) $this->id,
            'title' => $this->name,
            'subtitle' => $attributes['نام انگلیسی'] ?? '',
            'link' => rtrim(config('settings.website_base_url'), '/').'/product/'.rawurlencode($this->url),
            'image_link' => $this->images->pluck('url')->filter()->map(
                fn ($url) => preg_match('~^https?://~i', $url) ? $url : $imageBase.ltrim($url, '/')
            )->unique()->values()->all(),
            'availability' => $available->isNotEmpty() ? 'in stock' : 'out of stock',
            'regular_price' => $selected['regular'] ?? 0,
            'sale_price' => $selected['sale'] ?? 0,
            'category' => $category?->name ?? '',
            'brand' => $this->brand?->name ?? '',
            'description' => (object) $attributes,
            // This catalog has no dedicated GTIN column; never substitute an internal ID.
            'GTIN' => $attributes['GTIN'] ?? $attributes['شناسه جهانی کالا'] ?? null,
            'color' => $selected['color']->color_name ?? '',
            'delivery_time' => isset($selected) ? max(0, (int) $selected['color']->delivery_delay) : null,
        ];
    }

    private function plainText(?string $value): string
    {
        return trim(html_entity_decode(strip_tags($value ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }
}
