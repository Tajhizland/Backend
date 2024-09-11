<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Option extends Model
{
    protected $guarded=["id"];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function optionItems(): HasMany
    {
        return $this->hasMany(OptionItem::class);
    }
}
