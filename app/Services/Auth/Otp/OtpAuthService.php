<?php

namespace App\Services\Auth\Otp;

use App\Exceptions\BreakException;
use App\Repositories\MobileVerification\MobileVerificationRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Sms\SmsServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;

class OtpAuthService implements OtpAuthServiceInterface
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
        $isNewUser = !$user;

        // کاربر جدید: ثبت‌نام خودکار بدون رمز عبور (کاربر بعدا می‌تواند از پروفایل رمز تعیین کند)
        if ($isNewUser) {
            $user = $this->userRepository->registerWithMobile($mobile);
            if (!$user)
                throw new BreakException(Lang::get("exceptions.register_error"));
        }

        $this->mobileVerificationRepository->setCompleted($pendingRequest->id);

        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            "token" => $token,
            "is_new_user" => $isNewUser,
        ];
    }

    public function setPassword($newPassword, $currentPassword = null)
    {
        $user = Auth::user();

        // اگر کاربر از قبل رمز دارد، تعیین رمز جدید نیازمند تایید رمز فعلی است
        if (!empty($user->password)) {
            if (empty($currentPassword) || !Hash::check($currentPassword, $user->password))
                throw new BreakException(Lang::get("exceptions.wrong_password"));
        }

        $this->userRepository->resetPassword($user->username, $newPassword);
        return true;
    }
}
