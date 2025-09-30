<?php

namespace App\Http\Resources\V1\SmsLog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

/** @see \App\Models\SmsLog */
class SmsLogCollection extends ResourceCollection
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
