<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $guarded=["id"];
    protected function casts(): array
    {
        return [
//            'start_date' => 'timestamp',
//            'end_date' => 'timestamp',
        ];
    }
}
