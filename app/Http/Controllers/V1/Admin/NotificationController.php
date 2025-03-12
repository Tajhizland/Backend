<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Notification\NotificationCollection;
use App\Services\Notification\NotificationService;
use Illuminate\Support\Facades\Lang;

class NotificationController extends Controller
{
    public function __construct(private NotificationService $notificationService)
    { }
    public function unSeen()
    {
        return $this->dataResponseCollection(new NotificationCollection($this->notificationService->getUnSeen()));
    }
    public function seen()
    {
        $this->notificationService->seen();
        return $this->successResponse(Lang::get("action.change", ["attr" => Lang::get("attr.notification")]));
    }
    public function dataTable()
    {
        return $this->dataResponseCollection(new NotificationCollection($this->notificationService->dataTable()));
    }
}
