<?php

namespace App\Listeners;

use App\Events\OrderPaidEvent;
use App\Repositories\Notification\NotificationRepositoryInterface;

class SendOrderConfirmationNotification
{
    public function __construct
    (
        private NotificationRepositoryInterface $notificationRepository
    )
    {
    }

    public function handle(OrderPaidEvent $event): void
    {
        $title = config("notification.order.title");
        $link = config("notification.order.link");
        $message = config("notification.order.message");
        $type = config("notification.order.type");
        $this->notificationRepository->createNotification($title, $message, $link, $type);
    }
}
