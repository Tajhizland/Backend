<?php

namespace App\Listeners;

use App\Events\NewContactEvent;
use App\Repositories\Notification\NotificationRepositoryInterface;

class NewContactListener
{
    public function __construct(
        private NotificationRepositoryInterface $notificationRepository
    )
    {
    }

    public function handle(NewContactEvent $event): void
    {
        $title = config("notification.contact.title");
        $link = config("notification.contact.link");
        $message = config("notification.contact.message");
        $type = config("notification.contact.type");
        $this->notificationRepository->createNotification($title, $message, $link, $type);
    }
}
