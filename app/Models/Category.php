<?php

namespace App\Models;

use App\Enums\CategoryStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Category extends Model
{
    protected $guarded=["id"];
    protected function casts(): array
    {
        return [
            'status' => CategoryStatus::class
        ];
    }

    protected function productCategory(): HasOne
    {
        return $this->hasOne(ProductCategory::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_categories');
    }

    protected function filters(): HasMany
    {
        return $this->hasMany(Filter::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where("status", CategoryStatus::Active->value);
    }

    public function getMinProductPrice()
    {
        $products = $this->products ;
        $minPrice = $products->flatMap(function ($product) {
            $minColorPrice = $product->getMinColorPrice();
            return $minColorPrice !== null ? [$minColorPrice] : [];
        })->min() ?? 0;
        return $minPrice;
    }
    public function getMaxProductPrice()
    {
        $products = $this->products ;
        $maxPrice = $products->flatMap(function ($product) {
            $minColorPrice = $product->getMaxColorPrice();
            return $minColorPrice !== null ? [$minColorPrice] : [];
        })->max() ?? 0;
        return $maxPrice;
    }
}
