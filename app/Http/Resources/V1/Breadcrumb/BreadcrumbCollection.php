<?php

namespace App\Http\Resources\V1\Breadcrumb;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BreadcrumbCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
        ];
    }
}
