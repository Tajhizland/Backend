<?php

namespace App\Models;

use App\Enums\ConceptStatus;
use App\Enums\MenuStatus;
use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $guarded = ["id"];

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')
            ->where(function ($query) {
                $query->whereNull('category_id')
                    ->orWhere('category_id', 0)
                    ->orWhereHas('category.products', function ($query) {
                        $query->where("status", ProductStatus::Active->value);
                    });
            });
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }
    public function scopeActive(Builder $query): Builder
    {
        return $query->where("status", MenuStatus::Active->value);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
