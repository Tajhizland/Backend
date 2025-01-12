<?php

namespace App\Http\Resources\V1\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmallsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "title"=>$this->name,
            "id"=>$this->id,
            "price"=>$this->getDiscountMinColorPrice()??$this->getMinColorPrice(),
            "old_price"=>$this->getMinColorPrice(),
            "category"=>$this->categories->first()->name ,
            "image"=>config("settings.image_base_url")."product/".$this->images->first()->url,
            "guarantee"=>$this->guaranty->name??"" ,
            "is_available"=>$this->getMinColorPrice()>0?true:false,
            "url"=>config("settings.website_base_url")."product/".$this->url,
        ];
    }
}
