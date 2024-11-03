<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryConcept extends Model
{
    protected $guarded=["id"];

    public function concept(): BelongsTo
    {
        return $this->belongsTo(concept::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
