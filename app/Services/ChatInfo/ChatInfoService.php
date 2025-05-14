<?php

namespace App\Services\ChatInfo;

use App\Repositories\ChatInfo\ChatInfoRepositoryInterface;

class ChatInfoService implements ChatInfoServiceInterface
{
    public function __construct
    (
        private ChatInfoRepositoryInterface $chatInfoRepository
    )
    {
    }

    public function sync($userId, $token)
    {
        $chatInfo = $this->chatInfoRepository->findByUserId($userId);

        if (!$chatInfo) {
            $this->chatInfoRepository->create(["user_id" => $userId, "token" => $token]);
            return $token;
        }

        return $chatInfo->token;
    }
}
