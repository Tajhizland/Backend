<?php

namespace App\Models;

use App\Enums\CommentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $guarded=["id"];
    protected function casts(): array
    {
        return [
            'status' => CommentStatus::class,
        ];
    }

    protected function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }


    public function scopePending(Builder $query): Builder
    {
        return $query->where("status", CommentStatus::Pending->value);
    }

    public function scopeConfirmed(Builder $query): Builder
    {
        return $query->where("status", CommentStatus::Confirmed->value);
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->where("status", CommentStatus::Rejected->value);
    }
}
