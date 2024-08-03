<?php

namespace App\Models;

use App\Enums\CartStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $guarded=["id"];
    protected function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    protected function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class,"cart_id","id");
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where("status", CartStatus::Active->value);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where("status", CartStatus::Completed->value);
    }

}
