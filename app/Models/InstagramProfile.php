<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramProfile extends Model
{
    protected $fillable = ['name', 'url', 'bio', 'avatar'];

    // Keep the current public profile until the administrator first saves it.
    protected $attributes = [
        'name' => 'تجهیزلند | تجهیزات کافه و رستوران',
        'url' => 'https://www.instagram.com/tajhizland_com/',
        'bio' => 'دنیای تجهیزات حرفه‌ای آشپزخانه، کافه و رستوران',
        'avatar' => null,
    ];
}
