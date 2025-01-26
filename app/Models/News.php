<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    protected $guarded=["id"];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class , "author");
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where("published", 1);
    }

}
