<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\Otp\CheckMobileRequest;
use App\Http\Requests\V1\Auth\Otp\SendOtpRequest;
use App\Http\Requests\V1\Auth\Otp\VerifyOtpRequest;
use App\Services\Auth\Otp\OtpAuthServiceInterface;
use Illuminate\Support\Facades\Lang;

class OtpAuthController extends Controller
{
    public function __construct(private OtpAuthServiceInterface $otpAuthService)
    {
    }

    /**
     * مرحله اول فرم یکپارچه: بررسی شماره موبایل
     * خروجی مشخص می‌کند کاربر وجود دارد و رمز دارد یا خیر تا فرانت مرحله بعد را انتخاب کند
     */
    public function check(CheckMobileRequest $request)
    {
        $result = $this->otpAuthService->checkMobile($request->get("mobile"));
        return $this->dataResponse(
            $result,
            Lang::get("action.success", ["attr" => Lang::get("attr.mobile")])
        );
    }

    /**
     * ارسال کد یکبار مصرف (ورود با کد یکبار مصرف / ثبت‌نام کاربر جدید)
     */
    public function sendCode(SendOtpRequest $request)
    {
        $result = $this->otpAuthService->sendVerificationCode($request->get("mobile"));
        return $this->dataResponse(
            $result,
            Lang::get("action.send", ["attr" => Lang::get("attr.verify_code"), "to" => $request->get("mobile")])
        );
    }

    /**
     * تایید کد یکبار مصرف
     * کاربر موجود => توکن ورود | کاربر جدید => is_new_user=true و ادامه در مرحله ثبت‌نام
     */
    public function verifyCode(VerifyOtpRequest $request)
    {
        $result = $this->otpAuthService->verifyCode($request->get("mobile"), $request->get("code"));
        return $this->dataResponse(
            $result,
            Lang::get("action.verify", ["attr" => Lang::get("attr.verify_code")])
        );
    }
}
