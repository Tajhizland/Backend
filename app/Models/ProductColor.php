<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductColor extends Model
{
    protected $guarded=["id"];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function stock():HasOne
    {
        return $this->hasOne(Stock::class, 'product_color_id');
    }
    public function price():HasOne
    {
        return $this->hasOne(Price::class,'product_color_id');
    }
    public function discountItem(): HasMany
    {
        return $this->hasMany(DiscountItem::class, "product_color_id", "id");
    }

}
