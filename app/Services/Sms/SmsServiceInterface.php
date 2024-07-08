<?php

namespace App\Services\Sms;

interface SmsServiceInterface
{
    public function send($receptor, $message);
    public function sendLockup($receptor, $code, $template);
}
