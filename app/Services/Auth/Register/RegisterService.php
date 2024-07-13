<?php

namespace App\Services\Auth\Register;

use App\Repositories\MobileVerification\MobileVerificationRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Sms\SmsServiceInterface;
use App\Traits\ApiResponse;

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
        try {
            $code = rand(10000, 99999);
            $user = $this->userRepository->findByUsername($mobile);
            if ($user)
                throw new \InvalidArgumentException("این شماره همراه قبلا ثبت شده است.");
            $pendingRequest = $this->mobileVerificationRepository->findPendingRequest($mobile);
            if ($pendingRequest)
                throw new \InvalidArgumentException("پس از ارسال کد تا " . config("settings.register.code_expire_minutes") . " دقیقه امکان ارسال مجدد کد وجود ندارد");
            $this->mobileVerificationRepository->setVerificationCode($mobile, $code);

            $sms = $this->smsService->sendLockup($mobile, $code, config("sms.kavenegar.template"));

            if (!$sms || !$sms["return"] || $sms["return"]["status"] != 200)
                throw new \InvalidArgumentException("متاسفانه خطایی جهت ارسال پیامک رخ داد");

            return true;
        }
        catch (\InvalidArgumentException $exception) {
            throw new \Exception($exception->getMessage());
        }
        catch (\Throwable $exception) {
            throw new \Error($exception);
        }
    }

    public function verifyCode($mobile, $code)
    {
        $pendingRequest = $this->mobileVerificationRepository->findPendingRequest($mobile);
        if (!$pendingRequest)
            throw new \InvalidArgumentException("درخواستی برای این شماره همراه وجود ندارد");
        if ($pendingRequest->code != $code)
            throw new \InvalidArgumentException("کد تایید صحیح نیست");
        $this->mobileVerificationRepository->setInProgress($pendingRequest->id);
        return true;
    }

    public function register($mobile, $password)
    {
        $pendingRequest = $this->mobileVerificationRepository->findInProgressRequest($mobile);
        if (!$pendingRequest)
            throw new \InvalidArgumentException("درخواستی برای این شماره همراه وجود ندارد");
        $user = $this->userRepository->register($mobile, $password);
        if ($user) {
            $this->mobileVerificationRepository->setCompleted($pendingRequest->id);
            $token = $user->createToken('Api')->accessToken;
            return $token;
        }
        throw new \InvalidArgumentException("خطا در ثبت نام");
    }


}
