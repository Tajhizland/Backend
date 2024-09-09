<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded=["id"];
    public function scopeUnseen(Builder $query): Builder
    {
        return $query->where("seen",0);
    }
}
