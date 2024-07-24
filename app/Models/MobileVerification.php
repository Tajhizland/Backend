<?php

namespace App\Models;

use App\Enums\CartStatus;
use App\Enums\MobileVerificationStatus;
use App\Enums\ResetPasswordStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MobileVerification extends Model
{
    protected $guarded=["id"];
    protected function casts()
    {
        return [
            'expire_at' => 'timestamp',
            'status' => MobileVerificationStatus::class,
         ];
    }

    public function scopeUnExpire(Builder $query): Builder
    {
        return $query->where("expire_at", ">", Carbon::now());
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where("status", CartStatus::Active->value);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where("status", CartStatus::Completed->value);
    }

}
