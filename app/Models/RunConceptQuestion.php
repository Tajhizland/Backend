<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RunConceptQuestion extends Model
{
    protected $guarded=["id"];
    public function parentQuestion(): BelongsTo
    {
        return $this->belongsTo(RunConceptQuestion::class, 'parent_question');
    }

    public function parentAnswer(): BelongsTo
    {
        return $this->belongsTo(RunConceptAnswer::class, 'parent_answer');
    }
}
