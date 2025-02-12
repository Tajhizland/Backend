<?php

namespace App\Models;

use App\Enums\CategoryStatus;
use App\Enums\ProductStatus;
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

    public function productCategory(): HasOne
    {
        return $this->hasOne(ProductCategory::class);
    }

    public function categoryConcepts(): HasOne
    {
        return $this->hasOne(ProductCategory::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_categories');
    }

    public function filters(): HasMany
    {
        return $this->hasMany(Filter::class);
    }
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')
            ->where(function ($query) {
                $query->whereNull('category_id')
                    ->orWhere('category_id', 0)
                    ->orWhereHas('category.products', function ($query) {
                        $query->where("status", ProductStatus::Active->value);
                    });
            });
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
