<?php

namespace App\Http\Resources\Search;

use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\Product\ProductCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SearchCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
         return [
            'categories' => new CategoryCollection($this["categories"]),
            'products' => new ProductCollection($this["products"]),
        ];
    }
}
