<?php

namespace App\Models;

use App\Enums\DeliveryStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected function casts(): array
    {
        return [
            'status'=>DeliveryStatus::class
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where("status", DeliveryStatus::Active->value);
    }
}
