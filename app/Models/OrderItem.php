<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected function productColor(): BelongsTo
    {
        return $this->belongsTo(ProductColor::class);
    }

    protected function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
