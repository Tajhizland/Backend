<?php

namespace App\Models;

use App\Enums\FilterStatus;
use App\Enums\FilterType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Filter extends Model
{
    protected function casts(): array
    {
        return [
            'type' => FilterType::class,
            'status' => FilterStatus::class
        ];
    }
    protected function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    protected function items(): HasMany
    {
        return $this->hasMany(FilterItem::class);
    }
    protected function activeItems(): HasMany
    {
        return $this->hasMany(FilterItem::class)->where("status",);
    }
}
