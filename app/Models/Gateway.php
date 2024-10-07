<?php

namespace App\Models;

use App\Enums\GatewayStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    protected $guarded=['id'];
    protected function casts(): array
    {
        return [
            'status' => GatewayStatus::class
        ];
    }
    public function scopeActive(Builder $query): Builder
    {
        return $query->where("status",GatewayStatus::Active->value);
    }
}
