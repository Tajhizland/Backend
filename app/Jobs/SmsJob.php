<?php

namespace App\Jobs;

use App\Services\Sms\SmsServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private $receptor, private $message, private $method, private $template)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(SmsServiceInterface $smsService): void
    {
        switch ($this->method) {
            case config("sms.kavenegar.method.send"):
                $smsService->send($this->receptor, $this->message);
                break;
            case config("sms.kavenegar.method.lockup"):
                $smsService->sendLockup($this->receptor, $this->message, $this->template);
            default:
                break;
        }
    }
}
