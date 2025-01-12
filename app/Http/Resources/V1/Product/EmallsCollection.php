<?php

namespace App\Http\Resources\V1\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

/** @see \App\Models\Product */
class EmallsCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        if ($this->resource instanceof LengthAwarePaginator)
            return [
                'products' => $this->collection,
                'pages_count' => $this->lastPage(),
                'item_per_page' => $this->perPage(),
                'total_items' => $this->total(),
            ];

        return [
            'products' => $this->collection,
        ];
    }
}
