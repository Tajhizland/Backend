<?php

namespace App\Services\HomepageVlog;

use App\DTOs\HomepageVlog\HomepageVlogUpdateDto;

interface HomepageVlogServiceInterface
{
    public function get(): mixed;

    public function find(int $id): mixed;

    public function update(HomepageVlogUpdateDto $dto): bool;
}
