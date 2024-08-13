<?php

namespace App\Listeners;

use App\Events\CommentSubmitEvent;
use App\Repositories\Notification\NotificationRepositoryInterface;

class SendCommentSubmitNotification
{
    public function __construct
    (
        private NotificationRepositoryInterface $notificationRepository
    )
    {
    }

    public function handle(CommentSubmitEvent $event): void
    {
        $title = config("notification.comment.title");
        $link = config("notification.comment.link");
        $message = config("notification.comment.message");
        $type = config("notification.comment.type");
        $this->notificationRepository->createNotification($title, $message, $link, $type);
    }
}
