<?php

namespace App\Notifications;

use App\Services\Sms\Sms;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SmsNotification extends Notification
{
    protected $phone;
    protected $message;

    public function __construct($phone, $message) {
        $this->phone = $phone;
        $this->message = $message;
    }

    public function via($notifiable) {
        return ['sms'];
    }

    public function toSms($notifiable) {
        return (new Sms())->send($this->phone, $this->message);
    }
}
