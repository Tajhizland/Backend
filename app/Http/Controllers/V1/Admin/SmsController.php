<?php

namespace App\Http\Controllers\V1\Admin;

use App\Enums\SmsLogStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Sms\SendToContactRequest;
use App\Http\Requests\Admin\Sms\SmsRequest;
use App\Http\Resources\SmsLogItem\SmsLogItemResource;
use App\Jobs\GroupContactSmsMarketingJob;
use App\Jobs\GroupUserSmsMarketingJob;
use App\Services\SmsLog\SmsLogServiceInterface;
use App\Services\SmsLogItem\SmsLogItemServiceInterface;
use App\Http\Resources\SmsLog\SmsLogResource;

class SmsController extends Controller
{
    public function __construct
    (
        private readonly SmsLogServiceInterface     $smsLogService,
        private readonly SmsLogItemServiceInterface $smsLogItemService
    )
    {
    }

    public function dataTable()
    {
        $response = $this->smsLogService->dataTable();
        return $this->dataResponseCollection(SmsLogResource::collection($response));
    }

    public function itemDataTable($id)
    {
        $response = $this->smsLogItemService->dataTable($id);
        return $this->dataResponseCollection(SmsLogItemResource::collection($response));
    }

    public function viewItem($id)
    {
        $response = $this->smsLogItemService->find($id);
        return $this->dataResponse(SmsLogItemResource::make($response));
    }

    public function send(SmsRequest $request)
    {
        $smsLog = $this->smsLogService->store("users", SmsLogStatus::Pending->value);
        GroupUserSmsMarketingJob::dispatch($request->get("message"), $smsLog, $request->get("userIds"));
        return $this->successResponse(__("action.queued", ["attr" => __("attr.sms")]));
    }

    public function sendToContact(SendToContactRequest $request)
    {
        $smsLog = $this->smsLogService->store("phone-bock", SmsLogStatus::Pending->value);
        GroupContactSmsMarketingJob::dispatch($request->get("message"), $smsLog, $request->get("mobiles"));
        return $this->successResponse(__("action.queued", ["attr" => __("attr.sms")]));
    }
}
