<?php

namespace App\Models;

use App\Enums\CommentStatus;
use App\Enums\ProductColorStatus;
use App\Enums\ProductStatus;
use Carbon\Carbon;
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

    public function groupItems(): HasMany
    {
        return $this->hasMany(GroupProduct::class, "group_id", "id");
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy("sort")->orderBy("id");
    }

    public function stockOf(): HasOne
    {
        return $this->hasOne(Product::class, "id", "stock_of");
    }

    public function videos(): HasMany
    {
        return $this->hasMany(ProductVideo::class);
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

    public function unboxing(): BelongsTo
    {
        return $this->belongsTo(Vlog::class, "unboxing_video");
    }

    public function intro(): BelongsTo
    {
        return $this->belongsTo(Vlog::class, "intro_video");
    }

    public function usage(): BelongsTo
    {
        return $this->belongsTo(Vlog::class, "usage_video");
    }

    public function getMinColorPrice()
    {
        return $this->prices()->min("price");
    }

    public function getDiscountMinColorPrice()
    {
        return $this->prices()->where("discount", "<>", 0)->min("discount");
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
        })->orderBy('price', 'desc')->first();
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

    public function scopeIsProduct(Builder $query): Builder
    {
        return $query->where("type", "product");
    }

    public function scopeIsGroup(Builder $query): Builder
    {
        return $query->where("type", "group");
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
            $query->whereHas("discountItem", function ($subQuery) {
                $subQuery->where(function ($subQuery2) {
                    $subQuery2->whereNull("discount_expire_time")->orWhere("discount_expire_time", ">", Carbon::now());
                })->whereHas("discount", function ($subQuery2) {
                    $subQuery2->where("status", 1)->where(function ($subQuery3) {
                        $subQuery3->whereNull("start_date")->orWhere("start_date", "<", Carbon::now());
                    })->where(function ($subQuery3) {
                        $subQuery3->whereNull("end_date")->orWhere("end_date", ">", Carbon::now());
                    });
                });
            })->
            where("status", "<>", ProductColorStatus::DeActive->value)
                ->whereHas("price")->whereHas("stock", function ($q) {
                    $q->where("stock", ">", 0);
                });
        });
    }

    public function scopeIsStock(Builder $query): Builder
    {
        return $query->where("is_stock", 1);
    }

    public function scopeIsNotStock(Builder $query): Builder
    {
        return $query->where("is_stock", 0);
    }

    public function scopeCustomOrder(Builder $query): Builder
    {
        return $query->orderByRaw("
        (
            SELECT COUNT(*)
            FROM product_colors
            INNER JOIN stocks ON product_colors.id = stocks.product_color_id
            WHERE product_colors.product_id = products.id
              AND stocks.stock > 0
        ) = 0
    ")->orderBy("sort");
//        return $query->orderByRaw("
//            (SELECT MAX(stocks.stock)
//             FROM product_colors
//             INNER JOIN stocks
//             ON product_colors.id = stocks.product_color_id
//             WHERE product_colors.product_id = products.id
//            ) DESC
//        ")->orderBy("sort");

    }

    public function scopeWithActiveColor(Builder $query): Builder
    {
        return $query->with(["activeProductColors" => function ($query) {
            $query->with(["stock", "discountItem" => function ($subQuery) {
                $subQuery->where(function ($subQuery2) {
                    $subQuery2->whereNull("discount_expire_time")->orWhere("discount_expire_time", ">", Carbon::now());
                })->whereHas("discount", function ($subQuery2) {
                    $subQuery2->where("status", 1)->where(function ($subQuery3) {
                        $subQuery3->whereNull("start_date")->orWhere("start_date", "<", Carbon::now());
                    })->where(function ($subQuery3) {
                        $subQuery3->whereNull("end_date")->orWhere("end_date", ">", Carbon::now());
                    });
                })->latest("discount_id")->limit(1);
            }, "discountItem.discount"])->orderByDesc(Stock::select("stock")->whereColumn("product_color_id", "product_colors.id")->limit(1));
        }]);
    }
}
