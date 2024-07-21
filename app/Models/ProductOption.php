<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductOption extends Model
{
    protected function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected function optionItem(): BelongsTo
    {
        return $this->belongsTo(OptionItem::class);
    }
}
