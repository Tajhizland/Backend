<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Http;


class SmsService implements SmsServiceInterface
{
    private $baseUrl;
    private $token;
    private $sender;
    private $sendMethod;
    private $lockupMethod;

    public function __construct()
    {
        $this->baseUrl = config("sms.kavenegar.base_url");
        $this->token = config("sms.kavenegar.token");
        $this->sender = config("sms.kavenegar.number");
        $this->sendMethod = config("sms.kavenegar.method.send");
        $this->lockupMethod = config("sms.kavenegar.method.lockup");
    }

    public function send($receptor, $message)
    {
        $data = [
            'receptor' => $receptor,
            'sender' => $this->sender,
            'message' => $message
        ];
        return Http::get($this->getFullUrl($this->sendMethod), $data)->json();
    }

    public function sendLockup($receptor, $code, $template)
    {
        $data = [
            'receptor' => $receptor,
            'token' => $code,
            'template' => $template
        ];
        return Http::get($this->getFullUrl($this->lockupMethod), $data)->json();
    }

    private function getFullUrl($method): string
    {
        return $this->baseUrl . $this->token . '/' . $method;
    }
}
