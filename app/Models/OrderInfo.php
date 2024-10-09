<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderInfo extends Model
{
    protected $guarded=["id"];
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
}
