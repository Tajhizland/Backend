<?php

namespace App\Http\Resources\SampleImage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SampleImageCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
        ];
    }
}
