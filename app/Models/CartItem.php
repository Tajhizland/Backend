<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

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
    protected function product():  HasOneThrough
    {
        return $this->hasOneThrough(Product::class, ProductColor::class ,"product_id","id","product_color_id");

    }
}
