<?php

namespace App\Http\Controllers\V1\Auth;

use App\DTOs\Auth\MobileDto;
use App\DTOs\Auth\VerifyCodeDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Otp\CheckMobileRequest;
use App\Http\Requests\Auth\Otp\SendOtpRequest;
use App\Http\Requests\Auth\Otp\VerifyOtpRequest;
use App\Services\Auth\Otp\OtpAuthServiceInterface;

class OtpAuthController extends Controller
{
    public function __construct(private readonly OtpAuthServiceInterface $otpAuthService)
    {
    }

    /**
     * مرحله اول فرم یکپارچه: بررسی شماره موبایل
     * خروجی مشخص می‌کند کاربر وجود دارد و رمز دارد یا خیر تا فرانت مرحله بعد را انتخاب کند
     */
    public function check(CheckMobileRequest $request)
    {
        $result = $this->otpAuthService->checkMobile((new MobileDto(...$request->validated()))->mobile);
        return $this->dataResponse(
            $result,
            __("action.success", ["attr" => __("attr.mobile")])
        );
    }

    /**
     * ارسال کد یکبار مصرف (ورود با کد یکبار مصرف / ثبت‌نام کاربر جدید)
     */
    public function sendCode(SendOtpRequest $request)
    {
        $dto = new MobileDto(...$request->validated());
        $result = $this->otpAuthService->sendVerificationCode($dto->mobile);
        return $this->dataResponse(
            $result,
            __("action.send", ["attr" => __("attr.verify_code"), "to" => $dto->mobile])
        );
    }

    /**
     * تایید کد یکبار مصرف
     * کاربر موجود => توکن ورود | کاربر جدید => is_new_user=true و ادامه در مرحله ثبت‌نام
     */
    public function verifyCode(VerifyOtpRequest $request)
    {
        $dto = new VerifyCodeDto(...$request->validated());
        $result = $this->otpAuthService->verifyCode($dto->mobile, $dto->code);
        return $this->dataResponse(
            $result,
            __("action.verify", ["attr" => __("attr.verify_code")])
        );
    }
}
