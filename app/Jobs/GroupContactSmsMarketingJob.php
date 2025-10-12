<?php

namespace App\Jobs;

use App\Enums\SmsLogStatus;
use App\Services\Sms\SmsServiceInterface;
use App\Services\SmsLogItem\SmsLogItemServiceInterface;
use App\Services\User\UserServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GroupContactSmsMarketingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 36000;
    private $message;
    private $smsLog;
    private array $mobiles;

    public function __construct(
        $message,
        $smsLog,
        $mobiles = [],
    )
    {
        $this->message = $message;
        $this->smsLog = $smsLog;
        $this->mobiles = $mobiles;
    }

    public function handle(SmsServiceInterface $smsService, SmsLogItemServiceInterface $smsLogItemService): void
    {

        foreach ($this->mobiles as $mobile) {
            try {
                $sms = $smsService->send($mobile, $this->message);
                $smsLogItemService->store($this->smsLog->id, $mobile, $this->message, true);
            } catch (\Throwable $throwable) {
                $smsLogItemService->store($this->smsLog->id, $mobile, $this->message, false);
                continue;
            }

        }

        $this->smsLog->status = SmsLogStatus::Completed->value;
        $this->smsLog->save();
    }
}
