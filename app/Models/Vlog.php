<?php

namespace App\Models;

use App\Enums\VlogStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Vlog extends Model
{
    protected $guarded=["id"];

    public function scope(Builder $query): Builder
    {
        return $query->where("status",VlogStatus::Active->value);

    }
}
