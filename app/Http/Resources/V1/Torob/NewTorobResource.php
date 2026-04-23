<?php

namespace App\Http\Resources\V1\Torob;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewTorobResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $availability = false;
        $minPrice = PHP_INT_MAX;
        $minDiscountPrice = PHP_INT_MAX;

        foreach ($this->activeProductColors as $colors) {
            if ($colors->price->price < $minPrice && $colors->price->price != 0) {
                $minPrice = $colors->price->price;
                $minDiscountPrice = ($colors->activeDiscountItem && @$colors->activeDiscountItem[0]) ? $colors->activeDiscountItem[0]->discount_price : $colors->price->price;
            }
        }
        foreach ($this->stocks as $stock) {
            if ($stock->stock > 0) {
                $availability = true;
            }
        }
        return [
            "page_unique" => $this->id . "",
            "spec"=>[],
            "title" => $this->name,
            "page_url" => "https://tajhizland.com/product/" . $this->url,
            "current_price" => $minDiscountPrice,
            "availability" => $availability,
            "old_price" => $minPrice,
            "date_added" => $this->created_at,
            "image_links" => [config("settings.image_base_url")."product/".$this->images->first()->url],
        ];
    }
}
