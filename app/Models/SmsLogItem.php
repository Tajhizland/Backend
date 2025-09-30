<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsLogItem extends Model
{
    protected $guarded=["id"];
    public function smsLog(): BelongsTo
    {
        return $this->belongsTo(SmsLog::class);
    }

    protected function casts(): array
    {
        return [
            'is_send' => 'boolean',
        ];
    }
}
