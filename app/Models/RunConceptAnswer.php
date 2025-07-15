<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RunConceptAnswer extends Model
{
    public function runConceptQuestion(): BelongsTo
    {
        return $this->belongsTo(RunConceptQuestion::class);
    }
}
