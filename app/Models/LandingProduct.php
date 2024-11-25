<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandingProduct extends Model
{
    protected $guarded=["id"];

    public function landing(): BelongsTo
    {
        return $this->belongsTo(Landing::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
