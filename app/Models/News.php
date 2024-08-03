<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $guarded=["id"];

    public function scopePublished(Builder $query): Builder
    {
        return $query->where("published", 1);
    }

}
