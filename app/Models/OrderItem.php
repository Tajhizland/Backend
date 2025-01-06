<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $guarded=["id"];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productColor(): BelongsTo
    {
        return $this->belongsTo(ProductColor::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    public function guaranty(): BelongsTo
    {
        return $this->belongsTo(Guaranty::class);
    }
}
