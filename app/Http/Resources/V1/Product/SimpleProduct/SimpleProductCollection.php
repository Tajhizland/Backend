<?php

namespace App\Http\Resources\V1\Product\SimpleProduct;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

/** @see \App\Models\Product */
class SimpleProductCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        if ($this->resource instanceof LengthAwarePaginator)
            return [
                'data' => $this->collection,
                'meta' => [
                    'total' => $this->total(),
                    'current_page' => $this->currentPage(),
                    'last_page' => $this->lastPage(),
                    'per_page' => $this->perPage(),
                ],
            ];

        return [
            'data' => $this->collection,
        ];
    }
}