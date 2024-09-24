<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecialProduct extends Model
{
    protected $guarded=["id"];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
