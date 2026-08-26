<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\Notification\NotificationService;
use Illuminate\Support\Facades\Lang;
use App\Http\Resources\Notification\NotificationResource;

class NotificationController extends Controller
{
    public function __construct(private NotificationService $notificationService)
    { }
    public function unSeen()
    {
        return $this->dataResponseCollection(NotificationResource::collection($this->notificationService->getUnSeen()));
    }
    public function seen()
    {
        $this->notificationService->seen();
        return $this->successResponse(Lang::get("action.change", ["attr" => Lang::get("attr.notification")]));
    }
    public function dataTable()
    {
        return $this->dataResponseCollection(NotificationResource::collection($this->notificationService->dataTable()));
    }
}
