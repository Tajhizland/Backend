<?php

namespace App\Models;

use App\Enums\PageStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $guarded=["id"];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where("status", PageStatus::Active->value);
    }
}
