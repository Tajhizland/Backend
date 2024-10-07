<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FilterItem extends Model
{
    protected $guarded=["id"];

    protected function filter(): BelongsTo
    {
        return $this->belongsTo(Filter::class);
    }
}
