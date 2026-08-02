<?php

namespace App\Services\Auth\Otp;

interface OtpAuthServiceInterface
{
    /**
     * بررسی شماره موبایل جهت تعیین مرحله بعدی فرم (ورود با رمز / ورود با کد / ثبت‌نام)
     * @return array{exists: bool, has_password: bool}
     */
    public function checkMobile($mobile);

    /**
     * ارسال کد یکبار مصرف برای ورود یا ثبت‌نام
     * @return array{is_new_user: bool}
     */
    public function sendVerificationCode($mobile);

    /**
     * تایید کد یکبار مصرف
     * کاربر موجود => ورود و صدور توکن | کاربر جدید => ورود به مرحله اصلی ثبت‌نام (بدون توکن)
     * @return array{is_new_user: bool, token: string|null}
     */
    public function verifyCode($mobile, $code);
}
