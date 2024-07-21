<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductFilter extends Model
{
    protected function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected function filter(): BelongsTo
    {
        return $this->belongsTo(Filter::class);
    }

    protected function filterItem(): BelongsTo
    {
        return $this->belongsTo(FilterItem::class);
    }
}
