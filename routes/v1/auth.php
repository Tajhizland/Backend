<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    return "Welcome Auth";
});

Route::get('/me', [\App\Http\Controllers\V1\Auth\MeController::class,"me"])->middleware("auth:sanctum");
Route::put('/me', [\App\Http\Controllers\V1\Auth\MeController::class,"update"])->middleware("auth:sanctum");
Route::post('/logout', [\App\Http\Controllers\V1\Auth\MeController::class,"logout"])->middleware("auth:sanctum");


Route::post('/login', [\App\Http\Controllers\V1\Auth\LoginController::class,"login"]);

/****************** فلوی جدید ورود/ثبت‌نام یکپارچه (کد یکبار مصرف) ******************/
// مرحله ۱: بررسی شماره موبایل (کاربر وجود دارد؟ رمز دارد؟)
Route::post('/check', [\App\Http\Controllers\V1\Auth\OtpAuthController::class,"check"]);
// ارسال کد یکبار مصرف (هم برای ورود کاربر موجود، هم ثبت‌نام کاربر جدید)
Route::post('/otp/send', [\App\Http\Controllers\V1\Auth\OtpAuthController::class,"sendCode"]);
// تایید کد یکبار مصرف (کاربر موجود => توکن ورود | کاربر جدید => ادامه در مرحله ثبت‌نام)
Route::post('/otp/verify', [\App\Http\Controllers\V1\Auth\OtpAuthController::class,"verifyCode"]);
// مرحله اصلی ثبت‌نام کاربر جدید (بعد از تایید کد) از همان endpoint موجود انجام می‌شود:
// POST /auth/register  با فیلدهای: mobile, name, last_name, national_code, password, password_confirmation

Route::post('/register/send-code', [\App\Http\Controllers\V1\Auth\RegisterController::class,"sendVerificationCode"]);
Route::post('/register/verify-code', [\App\Http\Controllers\V1\Auth\RegisterController::class,"verifyCode"]);
Route::post('/register', [\App\Http\Controllers\V1\Auth\RegisterController::class,"register"]);

Route::post('/reset-password/send-code', [\App\Http\Controllers\V1\Auth\ResetPasswordController::class,"sendVerificationCode"]);
Route::post('/reset-password/verify-code', [\App\Http\Controllers\V1\Auth\ResetPasswordController::class,"verifyCode"]);
Route::post('/reset-password', [\App\Http\Controllers\V1\Auth\ResetPasswordController::class,"reset"]);

Route::post('/change-password', [\App\Http\Controllers\V1\Auth\ResetPasswordController::class,"reset"]);
