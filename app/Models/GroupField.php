<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupField extends Model
{
    protected $guarded=["id"];
    public function group(): BelongsTo
    {
        return $this->belongsTo(Product::class , "group_id");
    }
}
