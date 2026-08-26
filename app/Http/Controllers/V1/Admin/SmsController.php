<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Sms\SmsSendDto;
use App\DTOs\Sms\SmsSendToContactDto;
use App\Enums\SmsLogStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Sms\SendToContactRequest;
use App\Http\Requests\Admin\Sms\SmsRequest;
use App\Http\Resources\SmsLog\SmsLogResource;
use App\Http\Resources\SmsLogItem\SmsLogItemResource;
use App\Jobs\GroupContactSmsMarketingJob;
use App\Jobs\GroupUserSmsMarketingJob;
use App\Services\SmsLog\SmsLogServiceInterface;
use App\Services\SmsLogItem\SmsLogItemServiceInterface;

class SmsController extends Controller
{
    public function __construct(
        private readonly SmsLogServiceInterface     $smsLogService,
        private readonly SmsLogItemServiceInterface $smsLogItemService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(SmsLogResource::collection($this->smsLogService->dataTable()));
    }

    public function itemDataTable($id)
    {
        return $this->dataResponseCollection(SmsLogItemResource::collection($this->smsLogItemService->dataTable($id)));
    }

    public function showItem($id)
    {
        return $this->dataResponse(SmsLogItemResource::make($this->smsLogItemService->find($id)));
    }

    public function send(SmsRequest $request)
    {
        $dto = new SmsSendDto(...$request->validated());
        $smsLog = $this->smsLogService->store("users", SmsLogStatus::Pending->value);
        GroupUserSmsMarketingJob::dispatch($dto->message, $smsLog, $dto->userIds);
        return $this->successResponse(__("action.queued", ["attr" => __("attr.sms")]));
    }

    public function sendToContact(SendToContactRequest $request)
    {
        $dto = new SmsSendToContactDto(...$request->validated());
        $smsLog = $this->smsLogService->store("phone-bock", SmsLogStatus::Pending->value);
        GroupContactSmsMarketingJob::dispatch($dto->message, $smsLog, $dto->mobiles);
        return $this->successResponse(__("action.queued", ["attr" => __("attr.sms")]));
    }
}
