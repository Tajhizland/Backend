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
    protected function guaranty(): BelongsTo
    {
        return $this->belongsTo(Guaranty::class);
    }
    protected function productColor(): BelongsTo
    {
        return $this->belongsTo(ProductColor::class);
    }
    protected function product(): HasOneThrough
    {
        return $this->hasOneThrough(
            Product::class,            // جدول نهایی (Product)
            ProductColor::class,       // جدول واسطه (ProductColor)
            'id',                      // کلید خارجی در جدول واسطه (ProductColor) که به Product اشاره می‌کند
            'id',                      // کلید اصلی در جدول نهایی (Product)
            'product_color_id',        // کلید خارجی در جدول CartItem که به ProductColor اشاره می‌کند
            'product_id'               // کلید خارجی در جدول ProductColor که به Product اشاره می‌کند
        );
    }
}
