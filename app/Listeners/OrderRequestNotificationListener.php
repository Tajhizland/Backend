<?php

namespace App\Listeners;

use App\Events\OrderRequestEvent;
use App\Repositories\Notification\NotificationRepositoryInterface;

class OrderRequestNotificationListener
{
    public function __construct
    (
        private NotificationRepositoryInterface $notificationRepository
    )
    {
    }

    public function handle(OrderRequestEvent $event): void
    {
        $title = config("notification.onHoldOrder.title");
        $link = config("notification.onHoldOrder.link").$event->onHoldOrder->id;
        $message = config("notification.onHoldOrder.message");
        $type = config("notification.onHoldOrder.type");
        $this->notificationRepository->createNotification($title, $message, $link, $type);
    }
}
