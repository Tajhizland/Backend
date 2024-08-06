<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    protected function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    protected function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
