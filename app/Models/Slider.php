<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $guarded=["id"];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where("status",1);
    }
    public function scopeDesktop(Builder $query): Builder
    {
        return $query->where("type","desktop");
    }
    public function scopeMobile(Builder $query): Builder
    {
        return $query->where("type","mobile");
    }
}
