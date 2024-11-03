<?php

namespace App\Models;

use App\Enums\ConceptStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Concept extends Model
{
    protected $guarded = ["id"];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_concepts')->withPivot('display');
    }

    public function categoryConcepts(): HasMany
    {
        return $this->hasMany(CategoryConcept::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where("status", ConceptStatus::Active->value);
    }
}
