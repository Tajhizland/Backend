<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded=["id"];

    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,      // مدل مقصد
            'role_permission',      // اسم جدول pivot
            'role_id',              // کلید فیلد نقش در pivot
            'permission_id'         // کلید فیلد پرمیشن در pivot
        );
    }
}
