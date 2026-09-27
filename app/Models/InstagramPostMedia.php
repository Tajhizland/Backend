<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstagramPostMedia extends Model
{
    protected $table = "instagram_post_media";

    protected $guarded = ["id"];

    public function post(): BelongsTo
    {
        return $this->belongsTo(InstagramPost::class, "instagram_post_id");
    }
}
