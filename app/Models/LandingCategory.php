<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandingCategory extends Model
{
    protected $guarded=["id"];

    public function landing(): BelongsTo
    {
        return $this->belongsTo(Landing::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
