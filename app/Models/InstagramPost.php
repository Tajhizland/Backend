<?php

namespace App\Models;

use App\Enums\InstagramPostStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstagramPost extends Model
{
    protected $guarded = ["id"];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "author");
    }

    public function media(): HasMany
    {
        return $this->hasMany(InstagramPostMedia::class)->orderBy("sort")->orderBy("id");
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where("status", InstagramPostStatus::Active->value);
    }
}
