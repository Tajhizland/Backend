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
     * تایید کد و ورود/ثبت‌نام کاربر و صدور توکن
     * @return array{token: string, is_new_user: bool}
     */
    public function verifyCode($mobile, $code);

    /**
     * تعیین/تغییر رمز عبور برای کاربر لاگین‌شده
     * کاربری که با کد یکبار مصرف ثبت‌نام کرده (بدون رمز) می‌تواند رمز تعیین کند
     * کاربری که رمز دارد باید رمز فعلی را وارد کند
     */
    public function setPassword($newPassword, $currentPassword = null);
}
