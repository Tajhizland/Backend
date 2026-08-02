<?php

return [
    // شماره موبایل ادمین برای دریافت پیامک‌های اطلاع‌رسانی سفارش
    "admin_number" => env("ADMIN_SMS_NUMBER", "09353077652"),
    "kavenegar" => [
        "token" => "545A5A30322B6B646861622B7249693052326A597873705630322B734F6D417775756A646E56335A554A343D",
        "number" => "1000000020022",
        "base_url" => "http://api.kavenegar.com/v1/",
        "method" => [
            "send" => "sms/send.json",
            "lockup" => "verify/lookup.json"
        ],
        "template"=>"register"
    ]
];
