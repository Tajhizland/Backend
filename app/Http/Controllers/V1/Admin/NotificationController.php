<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Notification\NotificationCollection;
use App\Services\Notification\NotificationService;

class NotificationController extends Controller
{
    public function __construct(private NotificationService $notificationService)
    { }
    public function unSeen()
    {
        return $this->dataResponseCollection(new NotificationCollection($this->notificationService->getUnSeen()));
    }
    public function dataTable()
    {
        return $this->dataResponseCollection(new NotificationCollection($this->notificationService->dataTable()));
    }
}
