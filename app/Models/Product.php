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

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function productOptions(): HasMany
    {
        return $this->hasMany(ProductOption::class);
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
        return $this->hasMany(ProductColor::class)->whereIn("status", [ProductColorStatus::Active->value, ProductColorStatus::Limit->value]);
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

    public function guaranties(): BelongsToMany
    {
        return $this->belongsToMany(Guaranty::class, 'product_guaranties');
    }

    public function productCategories(): HasMany
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function productGuaranties(): HasMany
    {
        return $this->hasMany(ProductGuaranty::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function special(): HasOne
    {
        return $this->hasOne(SpecialProduct::class);
    }

    public function guaranty(): BelongsTo
    {
        return $this->belongsTo(Guaranty::class);
    }

    public function getMinColorPrice()
    {
        return $this->prices()->min("price");
    }

    public function getMinDiscountedPrice()
    {
        $minPriceColor = $this->prices()->
        whereHas("productColor", function ($query) {
            $query->whereHas("stock", function ($subQuery) {
                $subQuery->where("stock", ">", "0");
            })->where("status", ProductColorStatus::Active->value);
        })->orderBy('price')->first();
        if ($minPriceColor) {
            return $minPriceColor->discount != 0 ? $minPriceColor->discount : $minPriceColor->price;
        }
        return null;
    }
    public function getMaxDiscountedPrice()
    {
        $minPriceColor = $this->prices()->
        whereHas("productColor", function ($query) {
            $query->whereHas("stock", function ($subQuery) {
                $subQuery->where("stock", ">", "0");
            })->where("status", ProductColorStatus::Active->value);
        })->orderBy('price','desc')->first();
        if ($minPriceColor) {
            return $minPriceColor->discount != 0 ? $minPriceColor->discount : $minPriceColor->price;
        }
        return null;
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

    public function scopeHasColorHasStock(Builder $query): Builder
    {
        return $query->whereHas("productColors", function ($query) {
            $query->where("status", "<>", ProductColorStatus::DeActive->value)
                ->whereHas("stock", function ($subQuery) {
                    $subQuery->where("stock", ">", 0);
                });
        });
    }

    public function scopeMostPopular(Builder $query): Builder
    {
        return $query->orderBy("view", "desc");
    }

    public function scopeHasDiscount(Builder $query): Builder
    {
        return $query->whereHas("productColors", function ($query) {
            $query->where("status", "<>", ProductColorStatus::DeActive->value)
                ->whereHas("price", function ($q) {
                    $q->where("discount", ">", 0)->orderBy("discount", "desc");
                })->whereHas("stock", function ($q) {
                    $q->where("stock", ">", 0);
                });
        });
    }

    public function scopeCustomOrder(Builder $query): Builder
    {
        return $query  ->orderByRaw("
            (SELECT MAX(stocks.stock)
             FROM product_colors
             INNER JOIN stocks
             ON product_colors.id = stocks.product_color_id
             WHERE product_colors.product_id = products.id
            ) DESC
        ")->orderBy("sort");

    }
}
