<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $guarded=["id"];

    protected function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    protected function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    protected function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function scopeActive(Builder $query): Builder
    {
        return $query->where("active",1);
    }
}
