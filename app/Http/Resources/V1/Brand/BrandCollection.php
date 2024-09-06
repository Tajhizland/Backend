<?php

namespace App\Http\Resources\V1\Brand;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

/** @see \App\Models\Brand */
class BrandCollection extends ResourceCollection
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
