<?php

namespace App\Models;

use App\Enums\ResetPasswordStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class mobileVerification extends Model
{
    protected $guarded=["id"];
    protected function casts()
    {
        return [
            'expire_at' => 'timestamp',
         ];
    }

    public function scopeUnExpire(Builder $query): Builder
    {
        return $query->where("expire_at", ">", Carbon::now());
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where("status", ResetPasswordStatus::Pending);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where("status", ResetPasswordStatus::Completed);
    }

    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where("status", ResetPasswordStatus::InProgress);
    }
}
