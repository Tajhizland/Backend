<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductColor extends Model
{
    protected $guarded = ["id"];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class, 'product_color_id');
    }

    public function price(): HasOne
    {
        return $this->hasOne(Price::class, 'product_color_id');
    }

    public function discountItem(): HasMany
    {
        return $this->hasMany(DiscountItem::class, "product_color_id", "id");
    }

    public function activeDiscountItem(): HasMany
    {
        return $this->hasMany(DiscountItem::class, "product_color_id", "id")
            ->whereHas("discount", function ($query) {
                $query->where("status", 1)
                    ->where(function ($subQuery3) {
                        $subQuery3->whereNull("start_date")->orWhere("start_date", "<", Carbon::now());
                    })
                    ->where(function ($subQuery3) {
                        $subQuery3->whereNull("end_date")->orWhere("end_date", ">", Carbon::now());
                    });
            })->latest("discount_id");
    }

}
