<?php

return [
    "register" => [
        "code_expire_minutes" => 2,
        "status" => [
            "not_verified" => 0,
            "process" => 1,
            "verified" => 2,
        ]
    ],
    "reset_password" => [
        "code_expire_minutes" => 2,
        "status" => [
            "verified" => 1,
            "not_verified" => 0,
        ]
    ]
];
