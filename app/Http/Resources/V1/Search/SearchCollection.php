<?php

namespace App\Http\Resources\V1\Search;

use App\Http\Resources\V1\Category\CategoryCollection;
use App\Http\Resources\V1\Product\ProductCollection;
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
