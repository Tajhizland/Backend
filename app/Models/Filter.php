<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Filter extends Model
{
    protected function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
