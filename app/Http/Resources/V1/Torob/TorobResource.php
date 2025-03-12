<?php

namespace App\Http\Resources\V1\Torob;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TorobResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $availability = "outofstock";
        $minPrice = $this->prices[0]->price??0;
        $minDiscountPrice = $this->prices[0]->price??0;
         foreach ($this->prices as $price) {
            if ($price->price < $minPrice) {
                $minPrice = $price->price;
                $minDiscountPrice = $price->discount > 0 ? $price->discount : $price->price;
            }
        }
        foreach ($this->stocks as $stock) {
            if ($stock->stock > 0) {
                $availability = "instock";
            }
        }
        return [
            "product_id" => $this->id,
            "page_url" => "https://tajhizland.com/product/".$this->url,
            "price" => $minDiscountPrice,
            "availability" => $availability,
            "old_price" => $minPrice,
        ];
    }
}
