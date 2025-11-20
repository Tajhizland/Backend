<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignBanner extends Model
{
    protected $guarded = ["id"];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}
