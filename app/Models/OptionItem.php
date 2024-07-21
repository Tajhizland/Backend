<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OptionItem extends Model
{
    protected function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }
}
