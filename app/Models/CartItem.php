<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $guarded=["id"];

    protected function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    protected function productColor(): BelongsTo
    {
        return $this->belongsTo(ProductColor::class);
    }
}
