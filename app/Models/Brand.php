<?php

namespace App\Models;

use App\Enums\BrandStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $guarded=["id"];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where("status",BrandStatus::Active->value);
    }
}
