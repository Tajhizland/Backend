<?php

namespace App\Models;

use App\Enums\OnHoldOrderStatus;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $guarded=["id"];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderInfo(): BelongsTo
    {
        return $this->belongsTo(OrderInfo::class);
    }
    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class , "delivery_method" , "id");
    }
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Gateway::class , "payment_method" ,"id");
    }
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function onHoldOrder(): HasOne
    {
        return $this->hasOne(OnHoldOrder::class);
    }

    protected function casts()
    {
        return [
            'order_date' => 'timestamp',
        ];
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->whereIn("status",[
            OrderStatus::Paid->value,
            OrderStatus::Delivered->value,
            OrderStatus::Processing->value,
            OrderStatus::Shipped->value,
        ]);
    }
    public function scopeHasOnHoldPending(Builder $query): Builder
    {
        return $query->whereHas("onHoldOrder",function ($q){
           $q->where("status",OnHoldOrderStatus::Pending);
        });
    }
}
