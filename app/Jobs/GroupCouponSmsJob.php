<?php

namespace App\Jobs;

use App\Enums\SmsLogStatus;
use App\Repositories\Coupon\CouponRepositoryInterface;
use App\Services\Sms\SmsServiceInterface;
use App\Services\SmsLogItem\SmsLogItemServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Morilog\Jalali\Jalalian;

class GroupCouponSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 36000;

    public function __construct(
        private array $couponIds,
        private       $smsLog,
        private       $message = null,
    )
    {
    }

    public function handle(
        SmsServiceInterface        $smsService,
        CouponRepositoryInterface  $couponRepository,
        SmsLogItemServiceInterface $smsLogItemService
    ): void
    {
        $coupons = $couponRepository->getByIdsWithUser($this->couponIds);

        foreach ($coupons as $coupon) {
            if (!$coupon->user || !$coupon->user->username)
                continue;

            $message = $this->buildMessage($coupon);
            try {
                $smsService->send($coupon->user->username, $message);
                $smsLogItemService->store($this->smsLog->id, $coupon->user->username, $message, true);
            } catch (\Throwable $throwable) {
                $smsLogItemService->store($this->smsLog->id, $coupon->user->username, $message, false);
                continue;
            }
        }

        $this->smsLog->status = SmsLogStatus::Completed->value;
        $this->smsLog->save();
    }

    private function buildMessage($coupon): string
    {
        $amount = $coupon->percent
            ? $coupon->percent . " درصد"
            : ($coupon->price ? number_format($coupon->price) . " تومان" : "");

        $endTime = $coupon->end_time
            ? Jalalian::fromDateTime($coupon->end_time)->format('Y/m/d')
            : "";

        $replace = [
            "{name}" => $coupon->user->name ?? "",
            "{code}" => $coupon->code,
            "{amount}" => $amount,
            "{percent}" => $coupon->percent ?? "",
            "{price}" => $coupon->price ? number_format($coupon->price) : "",
            "{end_time}" => $endTime,
        ];

        $template = $this->message ?: $this->defaultTemplate($amount, $endTime);

        return str_replace(array_keys($replace), array_values($replace), $template);
    }

    private function defaultTemplate($amount, $endTime): string
    {
        $lines = ["کاربر گرامی {name}"];
        $lines[] = $amount
            ? "کد تخفیف {amount} اختصاصی شما : {code}"
            : "کد تخفیف اختصاصی شما : {code}";
        if ($endTime)
            $lines[] = "اعتبار تا {end_time}";

        return implode(PHP_EOL, $lines);
    }
}
