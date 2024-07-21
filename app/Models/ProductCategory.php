<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductCategory extends Model
{
    protected function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
