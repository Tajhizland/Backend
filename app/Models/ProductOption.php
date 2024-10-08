<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductOption extends Model
{
    protected $guarded=["id"];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function optionItem(): BelongsTo
    {
        return $this->belongsTo(OptionItem::class);
    }
}
