<?php

namespace App\Models;

use App\Enums\DirectUploadStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DirectUpload extends Model
{
    protected $guarded = ["id"];

    protected $casts = [
        'status' => DirectUploadStatus::class,
        'size' => 'integer',
        'confirmed_size' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function isMultipart(): bool
    {
        return !empty($this->upload_id);
    }
}
