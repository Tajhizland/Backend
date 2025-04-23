<?php

namespace App\Services\Auth\Register;

interface RegisterServiceInterface
{
    public function sendVerificationCode($mobile);
    public function verifyCode($mobile , $code);
    public function register($mobile, $password,$name,$last_name,$national_code);
}
