<?php

namespace App\Services\ChatInfo;

use App\DTOs\ChatInfo\ChatInfoSyncDto;

interface ChatInfoServiceInterface
{
    public function sync(ChatInfoSyncDto $dto): mixed;
}
