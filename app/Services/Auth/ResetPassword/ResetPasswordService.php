<?php

namespace App\Services\Auth\ResetPassword;

use App\Exceptions\BreakException;
use App\Repositories\ResetPassword\ResetPasswordRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Sms\SmsServiceInterface;
use Illuminate\Support\Facades\Lang;

class ResetPasswordService implements ResetPasswordServiceInterface
{

    public function __construct(
        private ResetPasswordRepositoryInterface $resetPasswordRepository,
        private UserRepositoryInterface $userRepository,
        private SmsServiceInterface $smsService,
    )
    {
    }

    public function sendVerificationCode($mobile)
    {
        $user=$this->userRepository->findByUsername($mobile);
        if(!$user)
            throw new BreakException(Lang::get("exceptions.user_not_find"));
         $pendingRequest = $this->resetPasswordRepository->findPendingRequest($mobile);
        if ($pendingRequest)
            throw new BreakException(Lang::get("exceptions.send_code_limit", ["time" => config("settings.reset_password.code_expire_minutes")]));
        $code = rand(10000, 99999);
        $this->resetPasswordRepository->setVerificationCode($mobile,$user->id, $code);

        $sms = $this->smsService->sendLockup($mobile, $code, config("sms.kavenegar.template"));

        if (!$sms || !$sms["return"] || $sms["return"]["status"] != 200)
            throw new BreakException($sms);
//            throw new BreakException(Lang::get("exceptions.sms_error"));
        return true;
    }

    public function verifyCode($mobile, $code)
    {
        $pendingRequest = $this->resetPasswordRepository->findPendingRequest($mobile);
        if (!$pendingRequest)
            throw new BreakException(Lang::get("exceptions.request_not_found"));
        if ($pendingRequest->code != $code)
            throw new BreakException(Lang::get("exceptions.code_is_not_valid"));

        $this->resetPasswordRepository->setInProgress($pendingRequest->id);
        return true;
    }

    public function reset($mobile, $password)
    {
        $pendingRequest = $this->resetPasswordRepository->findInProgressRequest($mobile);
        if (!$pendingRequest)
            throw new BreakException(Lang::get("exceptions.request_not_found"));
        $user = $this->userRepository->resetPassword($mobile, $password);
        if ($user) {
            $user=$this->userRepository->findByUsername($mobile);
            $this->resetPasswordRepository->setCompleted($pendingRequest->id);
            $token =  $user->createToken('auth-token')->plainTextToken;
            return $token;
        }
        throw new BreakException(Lang::get("exceptions.reset_password_error"));
    }
}
