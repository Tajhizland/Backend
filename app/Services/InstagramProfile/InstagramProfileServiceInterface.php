<?php

namespace App\Services\InstagramProfile;

use App\DTOs\InstagramProfile\InstagramProfileUpdateDto;
use App\Models\InstagramProfile;

interface InstagramProfileServiceInterface
{
    public function get(): InstagramProfile;

    public function update(InstagramProfileUpdateDto $dto): InstagramProfile;
}
