<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnHoldOrder extends Model
{
    protected $guarded=["id"];

    protected function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    protected function casts()
    {
        return [
            'expire_date' => 'timestamp',
            'review_date' => 'timestamp',
        ];
    }
}
