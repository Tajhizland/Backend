<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    protected function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
}
