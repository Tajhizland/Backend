<?php

namespace App\Services\ChatInfo;

use App\DTOs\ChatInfo\ChatInfoSyncDto;

use App\Repositories\ChatInfo\ChatInfoRepositoryInterface;

readonly class ChatInfoService implements ChatInfoServiceInterface
{
    public function __construct
    (
        private ChatInfoRepositoryInterface $chatInfoRepository
    )
    {
    }

    public function sync(ChatInfoSyncDto $dto): mixed
    {
        $userId = $dto->userId;
        $token = $dto->token;
        $chatInfo = $this->chatInfoRepository->findByUserId($userId);

        if (!$chatInfo) {
            $this->chatInfoRepository->create(["user_id" => $userId, "token" => $token]);
            return $token;
        }

        return $chatInfo->token;
    }
}
