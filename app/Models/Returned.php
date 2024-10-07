<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Returned extends Model
{
    protected $guarded=["id"];

    protected function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    protected function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }
}
