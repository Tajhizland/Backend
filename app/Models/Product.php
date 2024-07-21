<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Product extends Model
{
    protected $guarded=["id"];

    public function productColors(): HasMany
    {
        return $this->hasMany(ProductColor::class);
    }
    public function activeProductColors(): HasMany
    {
        return $this->hasMany(ProductColor::class)->where("status",1);
    }
    public function prices() :HasManyThrough
    {
        return $this->hasManyThrough(Price::class, ProductColor::class);
    }
    public function invoices() :HasManyThrough
    {
        return $this->hasManyThrough(Invoice::class, ProductColor::class);
    }
    public function getMinColorPrice()
    {
        return $this->prices()->min("price");
    }
    public function scopeActive(Builder $query): Builder
     {
        return $query->where("status",1);
    }
}
