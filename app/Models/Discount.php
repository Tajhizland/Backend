<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Discount extends Model
{
    public function productColor(): BelongsTo
    {
        return $this->belongsTo(ProductColor::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    protected function casts(): array
    {
        return [
            'discount_expire_time' => 'timestamp',
        ];
    }
}
