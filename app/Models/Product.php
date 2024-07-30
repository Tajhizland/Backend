<?php

namespace App\Models;

use App\Enums\ProductColorStatus;
use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Product extends Model
{
    protected $guarded = ["id"];

    protected function casts(): array
    {
        return [
            'status' => ProductStatus::class
        ];
    }

    public function productColors(): HasMany
    {
        return $this->hasMany(ProductColor::class);
    }
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function productFilters(): HasMany
    {
        return $this->hasMany(ProductFilter::class);
    }

    public function activeProductColors(): HasMany
    {
        return $this->hasMany(ProductColor::class)->where("status", 1);
    }

    public function prices(): HasManyThrough
    {
        return $this->hasManyThrough(Price::class, ProductColor::class);
    }

    public function stocks(): HasManyThrough
    {
        return $this->hasManyThrough(Stock::class, ProductColor::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function getMinColorPrice()
    {
        return $this->prices()->min("price");
    }
    public function getMaxColorPrice()
    {
        return $this->prices()->max("price");
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where("status", ProductStatus::Active->value);
    }

    public function scopeHasColor(Builder $query): Builder
    {
        return $query->whereHas("productColors", function ($query) {
            $query->where("status", "<>", ProductColorStatus::DeActive->value);
        });
    }
}
