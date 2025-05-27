<?php

namespace App\Listeners;

use App\Events\OrderPaymentRequestEvent;
use App\Repositories\Notification\NotificationRepositoryInterface;

class SendOrderPaymentRequestNotificationListener
{
    public function __construct
    (
        private NotificationRepositoryInterface $notificationRepository
    )
    {
    }

    public function handle(OrderPaymentRequestEvent $event): void
    {
        $title = config("notification.orderRequest.title");
        $link = config("notification.orderRequest.link").$event->order->id;
        $message = config("notification.orderRequest.message");
        $type = config("notification.orderRequest.type");
        $this->notificationRepository->createNotification($title, $message, $link, $type);
    }
}
