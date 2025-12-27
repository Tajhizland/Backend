<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cast extends Model
{
    protected $guarded = ["id"];

    public function vlog(): BelongsTo
    {
        return $this->belongsTo(Vlog::class);
    }

    public function castCategory(): BelongsTo
    {
        return $this->belongsTo(CastCategory::class);
    }
}
