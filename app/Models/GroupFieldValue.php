<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupFieldValue extends Model
{
    public function groupProduct(): BelongsTo
    {
        return $this->belongsTo(GroupProduct::class);
    }

    public function groupField(): BelongsTo
    {
        return $this->belongsTo(GroupField::class);
    }
}
