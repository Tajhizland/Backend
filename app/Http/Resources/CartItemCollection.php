<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/** @see \App\Models\CartItem */
class CartItemCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'cartItems' => $this->collection,
        ];
    }
}
