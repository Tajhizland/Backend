<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Category extends Model
{
    protected function productCategory(): HasOne
    {
        return $this->hasOne(ProductCategory::class);
    }
    public function products():BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_categories');
    }
    protected function filters(): HasMany
    {
        return $this->hasMany(Filter::class);
    }
}
