<?php

namespace App\Models;

 use App\Enums\MobileVerificationStatus;
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
        return $query->where("status", MobileVerificationStatus::Pending->value);
    }
    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where("status", MobileVerificationStatus::InProgress->value);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where("status", MobileVerificationStatus::Completed->value);
    }

}
