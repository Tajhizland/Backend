<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GroupProduct extends Model
{
    protected $guarded=["id"];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'group_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function value(): HasMany
    {
        return $this->hasMany(GroupFieldValue::class);
    }
}
