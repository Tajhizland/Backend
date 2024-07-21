<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function orderInfo(): BelongsTo
    {
        return $this->belongsTo(OrderInfo::class);
    }

    protected function casts()
    {
        return [
            'order_date' => 'timestamp',
        ];
    }
}
