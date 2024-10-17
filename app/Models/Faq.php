<?php

namespace App\Models;

use App\Enums\FaqStatus;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $guarded=["id"];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where("status", FaqStatus::Active->value);
    }

}
