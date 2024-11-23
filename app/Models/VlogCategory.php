<?php

namespace App\Models;

use App\Enums\VlogCategoryStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VlogCategory extends Model
{
    protected $guarded=["id"];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where("status",VlogCategoryStatus::Active->value);
    }
}
