<?php

namespace App\Models;

use App\Enums\BlogCategoryStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    protected $guarded=["id"];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where("status",BlogCategoryStatus::Active->value);
    }
}
