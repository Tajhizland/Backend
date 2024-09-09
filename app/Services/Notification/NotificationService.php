<?php

namespace App\Services\Notification;

use App\Repositories\Notification\NotificationRepositoryInterface;

class NotificationService implements NotificationServiceInterface
{
    public function __construct(
        private NotificationRepositoryInterface $notificationRepository
    )
    {
    }

    public function getUnSeen()
    {
        return $this->getUnSeen();
    }

    public function dataTable()
    {
        return $this->dataTable();
    }
}
