<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    protected $guarded = ["id"];

    protected function casts(): array
    {
        return [
        ];
    }

    public function mobileSliders(): HasMany
    {
        return $this->hasMany(CampaignSlider::class)->where("type", "desktop");
    }

    public function desktopSliders(): HasMany
    {
        return $this->hasMany(CampaignSlider::class)->where("type", "mobile");
    }
}
