<?php

namespace App\Models;

use App\Enums\GuarantyStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Guaranty extends Model
{
    protected $guarded=["id"];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where("status",GuarantyStatus::Active->value);
    }
}
