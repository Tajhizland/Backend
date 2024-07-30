<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    protected $guarded=["id"];

    public function productColor(): BelongsTo
    {
        return $this->belongsTo(ProductColor::class);
    }
}
