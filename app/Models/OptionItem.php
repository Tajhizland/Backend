<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OptionItem extends Model
{
    protected $guarded = ["id"];

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function productOption(): HasOne
    {
        return $this->hasOne(ProductOption::class);
    }
}
