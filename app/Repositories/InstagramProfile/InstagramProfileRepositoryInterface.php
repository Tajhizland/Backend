<?php

namespace App\Repositories\InstagramProfile;

use App\Models\InstagramProfile;

interface InstagramProfileRepositoryInterface
{
    public function get(): InstagramProfile;

    public function save(InstagramProfile $profile, array $attributes): bool;
}
