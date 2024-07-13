<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class mobileVerification extends Model
{
    protected $guarded=["id"];
    protected function casts()
    {
        return [
            'expire_at' => 'timestamp',
         ];
    }
}
