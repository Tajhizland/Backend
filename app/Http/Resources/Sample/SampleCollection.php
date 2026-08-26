<?php

namespace App\Http\Resources\Sample;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SampleCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
        ];
    }
}
