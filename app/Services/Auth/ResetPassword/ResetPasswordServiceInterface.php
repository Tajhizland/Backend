<?php

namespace App\Services\Auth\ResetPassword;

interface ResetPasswordServiceInterface
{
    public function sendVerificationCode($mobile);
    public function verifyCode($mobile , $code);
    public function reset($mobile, $password);
}
