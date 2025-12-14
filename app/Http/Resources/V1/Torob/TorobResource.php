<?php

namespace App\Http\Resources\V1\Torob;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TorobResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $availability = "outofstock";
        $minPrice = PHP_INT_MAX;
        $minDiscountPrice = PHP_INT_MAX;
//        foreach ($this->prices as $price) {
//            if ($price->price < $minPrice && $price->price != 0) {
//                $minPrice = $price->price;
//                $minDiscountPrice = $price->discount > 0 ? $price->discount : $price->price;
//            }
//        }
        foreach ($this->activeProductColors as $colors) {
            if ($colors->price->price < $minPrice && $colors->price->price != 0) {
                $minPrice = $colors->price->price;
                $minDiscountPrice = ($colors->activeDiscountItem && @$colors->activeDiscountItem[0]) ? $colors->activeDiscountItem[0]->discount_price : $colors->price->price;
            }
        }
        foreach ($this->stocks as $stock) {
            if ($stock->stock > 0) {
                $availability = "instock";
            }
        }
        return [
            "product_id" => $this->id,
            "page_url" => "https://tajhizland.com/product/" . $this->url,
            "price" => $minDiscountPrice,
            "availability" => $availability,
            "old_price" => $minPrice,
        ];
    }
}
