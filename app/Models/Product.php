<?php

namespace App\Models;

use App\Enums\CommentStatus;
use App\Enums\ProductColorStatus;
use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function confirmedComments(): HasMany
    {
        return $this->hasMany(Comment::class)->where("status", CommentStatus::Accepted->value);
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

    public function productCategories(): HasOne
    {
        return $this->hasOne(ProductCategory::class);
    }

    protected function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function getMinColorPrice()
    {
        return $this->prices()->min("price");
    }

    public function getRatingAvg()
    {
        return $this->comments()->Confirmed()->avg("rating");
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
    public function scopeMostPopular(Builder $query): Builder
    {
        return $query->orderBy("view","desc");
    }

    public function scopeHasDiscount(Builder $query): Builder
    {
        return $query->whereHas("productColors", function ($query) {
            $query->where("status", "<>", ProductColorStatus::DeActive->value)
                ->where("discount", ">", 0)
                ->orderBy("discount", "desc");
        });
    }
}
