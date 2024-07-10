<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class mobileVerification extends Model
{
    protected function casts()
    {
        return [
            'expire_at' => 'timestamp',
            'status' => \App\Enums\MobileVerification::class
        ];
    }
}
