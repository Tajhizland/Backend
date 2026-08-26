<?php

namespace App\Services\Notification;

use App\Repositories\Notification\NotificationRepositoryInterface;

readonly class NotificationService implements NotificationServiceInterface
{
    public function __construct(
        private NotificationRepositoryInterface $notificationRepository
    )
    {
    }

    public function getUnSeen()
    {
        return $this->notificationRepository->getUnSeen();
    }

    public function seen()
    {
        return $this->notificationRepository->seen();
    }

    public function dataTable()
    {
        return $this->notificationRepository->dataTable();
    }
}
