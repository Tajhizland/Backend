<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SampleVideo extends Model
{
    protected $guarded=["id"];

    public function vlog(): BelongsTo
    {
        return $this->belongsTo(Vlog::class);
    }
}
