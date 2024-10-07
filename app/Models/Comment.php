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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }


    public function scopePending(Builder $query): Builder
    {
        return $query->where("status", CommentStatus::Pending->value);
    }

    public function scopeConfirmed(Builder $query): Builder
    {
        return $query->where("status", CommentStatus::Accepted->value);
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->where("status", CommentStatus::Rejected->value);
    }
}
