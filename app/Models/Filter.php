<?php

namespace App\Models;

use App\Enums\FilterStatus;
use App\Enums\FilterType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Filter extends Model
{
    protected function casts(): array
    {
        return [
            'type' => FilterType::class,
            'status' => FilterStatus::class
        ];
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function items(): HasMany
    {
        return $this->hasMany(FilterItem::class);
    }
    public function productFilters(): HasOne
    {
        return $this->hasOne(ProductFilter::class);
    }
    public function activeItems(): HasMany
    {
        return $this->hasMany(FilterItem::class)->where("status",);
    }
}
