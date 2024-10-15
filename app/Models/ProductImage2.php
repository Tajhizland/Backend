<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage2 extends Model
{
    protected $table="product_images2";
    protected $guarded=["id"];
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
