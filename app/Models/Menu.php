<?php

namespace App\Models;

use App\Enums\ConceptStatus;
use App\Enums\MenuStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $guarded = ["id"];

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }
    public function scopeActive(Builder $query): Builder
    {
        return $query->where("status", MenuStatus::Active->value);
    }
}
