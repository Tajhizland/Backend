<?php

namespace App\Repositories\InstagramProfile;

use App\Models\InstagramProfile;

readonly class InstagramProfileRepository implements InstagramProfileRepositoryInterface
{
    public function __construct(private InstagramProfile $model) {}

    public function get(): InstagramProfile
    {
        // There is one shared profile for all Instagram posts.
        $profile = $this->model->newQuery()->find(1) ?? $this->model->newInstance();
        $profile->id = 1;

        return $profile;
    }

    public function save(InstagramProfile $profile, array $attributes): bool
    {
        return $profile->fill($attributes)->save();
    }
}
