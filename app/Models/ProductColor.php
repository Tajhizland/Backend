<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductColor extends Model
{
    protected $guarded=["id"];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function invoice():HasOne
    {
        return $this->hasOne(Invoice::class, 'product_color_id');
    }
    public function price():HasOne
    {
        return $this->hasOne(Price::class,'product_color_id');
    }
}
