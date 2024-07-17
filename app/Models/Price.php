<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Price extends Model
{
    protected $guarded=["id"];
    protected $attributes=[
        "discount" => 0
    ];

    public function productColor(): BelongsTo
    {
        return $this->belongsTo(ProductColor::class);
    }
}
