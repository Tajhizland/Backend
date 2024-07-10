<?php

namespace App\Services\Auth\Register;

use App\Repositories\MobileVerification\MobileVerificationRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Sms\SmsServiceInterface;

class RegisterService implements RegisterServiceInterface
{
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
        $code = rand(10000, 99999);
        $user = $this->userRepository->findByUsername($mobile);
        if ($user)
            return false;
        $pendingRequest = $this->mobileVerificationRepository->findPendingRequest($mobile);
        if ($pendingRequest)
            return false;
        $this->mobileVerificationRepository->setVerificationCode($mobile, $code);
        $sms = $this->smsService->sendLockup($mobile, $code, config("sms.kavenegar.template"));
        if ($sms && $sms->return && $sms->return->status == 200)
            return true;
        return false;
    }

    public function verifyCode($mobile, $code)
    {
        $pendingRequest = $this->mobileVerificationRepository->findPendingRequest($mobile);
        if (!$pendingRequest)
            return false;
        if ($pendingRequest->code != $code)
            return false;
        $this->mobileVerificationRepository->setInProgress($pendingRequest->id);
    }

    public function register($mobile, $password)
    {
        $pendingRequest = $this->mobileVerificationRepository->findInProgressRequest($mobile);
        if (!$pendingRequest)
            return false;
        $user = $this->userRepository->register($mobile, $password);
        if ($user) {
            $this->mobileVerificationRepository->setCompleted($pendingRequest->id);
            $token = $user->createToken('Api')->accessToken;
            return $token;
        }
        return false;
    }


}
