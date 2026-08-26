<?php

namespace App\Services\Auth\Otp;

use App\Exceptions\BreakException;
use App\Repositories\MobileVerification\MobileVerificationRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Sms\SmsServiceInterface;
use Illuminate\Support\Facades\Lang;

readonly class OtpAuthService implements OtpAuthServiceInterface
{
    public function __construct(
        private UserRepositoryInterface               $userRepository,
        private MobileVerificationRepositoryInterface $mobileVerificationRepository,
        private SmsServiceInterface                   $smsService
    )
    {
    }

    public function checkMobile($mobile)
    {
        $user = $this->userRepository->findByUsername($mobile);
        return [
            "exists" => (bool)$user,
            "has_password" => $user ? !empty($user->password) : false,
        ];
    }

    public function sendVerificationCode($mobile)
    {
        $user = $this->userRepository->findByUsername($mobile);

        $pendingRequest = $this->mobileVerificationRepository->findPendingRequest($mobile);
        if ($pendingRequest)
            throw new BreakException(Lang::get("exceptions.send_code_limit", ["time" => config("settings.register.code_expire_minutes")]));

        $code = rand(10000, 99999);
        $this->mobileVerificationRepository->setVerificationCode($mobile, $code);

        $sms = $this->smsService->sendLockup($mobile, $code, config("sms.kavenegar.template"));

        if (!$sms || !$sms["return"] || $sms["return"]["status"] != 200)
            throw new BreakException(Lang::get("exceptions.sms_error"));

        return ["is_new_user" => !$user];
    }

    public function verifyCode($mobile, $code)
    {
        $pendingRequest = $this->mobileVerificationRepository->findPendingRequest($mobile);
        if (!$pendingRequest)
            throw new BreakException(Lang::get("exceptions.request_not_found"));
        if ($pendingRequest->code != $code)
            throw new BreakException(Lang::get("exceptions.code_is_not_valid"));

        $user = $this->userRepository->findByUsername($mobile);

        // کاربر جدید: کد تایید می‌شود و درخواست به حالت InProgress می‌رود
        // اما کاربر ساخته نمی‌شود و توکنی صادر نمی‌شود؛ فرانت وارد مرحله اصلی ثبت‌نام می‌شود
        // و اطلاعات (نام، نام خانوادگی، کد ملی، رمز) را به POST /auth/register ارسال می‌کند
        if (!$user) {
            $this->mobileVerificationRepository->setInProgress($pendingRequest->id);
            return [
                "is_new_user" => true,
                "token" => null,
            ];
        }

        // کاربر موجود (ورود با کد یکبار مصرف): ورود مستقیم و صدور توکن
        $this->mobileVerificationRepository->setCompleted($pendingRequest->id);
        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            "is_new_user" => false,
            "token" => $token,
        ];
    }
}
