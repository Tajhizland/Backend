<?php

namespace App\Models;

use App\Enums\VlogStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vlog extends Model
{
    protected $guarded=["id"];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class , "author");
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where("status",VlogStatus::Active->value);
    }
}
