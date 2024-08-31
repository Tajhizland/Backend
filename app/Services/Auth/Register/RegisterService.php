<?php

namespace App\Services\Auth\Register;

use App\Exceptions\BreakException;
use App\Repositories\MobileVerification\MobileVerificationRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Sms\SmsServiceInterface;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Lang;

class RegisterService implements RegisterServiceInterface
{
    use  ApiResponse;

    public function __construct
    (
        private UserRepositoryInterface               $userRepository,
        private MobileVerificationRepositoryInterface $mobileVerificationRepository,
        private SmsServiceInterface                   $smsService
    )
    {
    }

    public function sendVerificationCode($mobile)
    {
        $pendingRequest = $this->mobileVerificationRepository->findPendingRequest($mobile);
        if ($pendingRequest)
            throw new BreakException(Lang::get("exceptions.send_code_limit", ["time" => config("settings.register.code_expire_minutes")]));

        $code = rand(10000, 99999);
        $this->mobileVerificationRepository->setVerificationCode($mobile, $code);

        $sms = $this->smsService->sendLockup($mobile, $code, config("sms.kavenegar.template"));

        if (!$sms || !$sms["return"] || $sms["return"]["status"] != 200)
            throw new BreakException(Lang::get("exceptions.sms_error"));
        return true;

    }

    public function verifyCode($mobile, $code)
    {
        $pendingRequest = $this->mobileVerificationRepository->findPendingRequest($mobile);
        if (!$pendingRequest)
            throw new BreakException(Lang::get("exceptions.request_not_found"));
        if ($pendingRequest->code != $code)
            throw new BreakException(Lang::get("exceptions.code_is_not_valid"));

        $this->mobileVerificationRepository->setInProgress($pendingRequest->id);
        return true;
    }

    public function register($mobile, $password)
    {
        $pendingRequest = $this->mobileVerificationRepository->findInProgressRequest($mobile);
        if (!$pendingRequest)
            throw new BreakException(Lang::get("exceptions.request_not_found"));
        $user = $this->userRepository->register($mobile, $password);
        if ($user) {
            $this->mobileVerificationRepository->setCompleted($pendingRequest->id);
            $token = $user->createToken('auth-token')->plainTextToken;
            return $token;
        }
        throw new BreakException(Lang::get("exceptions.register_error"));

    }


}
