<?php

namespace App\Http\Resources\V1\Admin\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/** @see \App\Models\Product */
class ProductCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'products' => $this->collection,
            'meta' => [
                'page' => $this->currentPage(),
                'pages' => $this->lastPage(),
                'perpage' => $this->perPage(),
                'rowIds' => $this->collection->pluck('id')->toArray()
            ],
         ];
    }
}
