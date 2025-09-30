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

class GroupSmsMarketingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 36000;
    private $type;
    private $message;
    private $smsLog;

    public function __construct(
        $type,
        $message,
        $smsLog,
    )
    {
        $this->type = $type;
        $this->message = $message;
        $this->smsLog = $smsLog;
    }

    public function handle(SmsServiceInterface $smsService, UserServiceInterface $userService, SmsLogItemServiceInterface $smsLogItemService): void
    {
        $users = [];
        if ($this->type == "has_order") {
            $users = $userService->getHasOrderUser();
        }
        if ($this->type == "has_not_order") {
            $users = $userService->getHasNotOrderUser();
        }
        if ($this->type == "has_active_cart") {
            $users = $userService->getHasActiveCartUser();
        }
        if ($this->type == "test") {
            $users [] = $userService->findById(2);
        }

        foreach ($users as $user) {
            try {
                $sms = $smsService->send($user->username, $this->message);
                $smsLogItemService->store($this->smsLog->id, $user->username, $this->message, true);
            } catch (\Throwable $throwable) {
                $smsLogItemService->store($this->smsLog->id, $user->username, $this->message, false);
                continue;
            }

        }

        $this->smsLog->status = SmsLogStatus::Completed->value;
        $this->smsLog->save();
    }
}
