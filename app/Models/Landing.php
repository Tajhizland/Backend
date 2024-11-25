<?php

namespace App\Models;

use App\Enums\LandingStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Landing extends Model
{
    protected $guarded = ["id"];

    public function landingProducts(): HasMany
    {
        return $this->hasMany(LandingProduct::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'landing_products');
    }
    public function landingBanner(): HasMany
    {
        return $this->hasMany(LandingBanner::class);
    }
    public function landingBannerImage(): HasMany
    {
        return $this->hasMany(LandingBanner::class)->where("slider",0);
    }
    public function landingBannerSlider(): HasMany
    {
        return $this->hasMany(LandingBanner::class)->where("slider",1);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'landing_categories');
    }

    public function landingCategories(): HasMany
    {
        return $this->hasMany(LandingCategory::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where("status", LandingStatus::Active->value);
    }
}
