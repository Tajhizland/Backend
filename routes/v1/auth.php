<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    return "Welcome Auth";
});

Route::get('/me', [\App\Http\Controllers\V1\Auth\MeController::class,"me"])->middleware("auth:sanctum");
Route::post('/update', [\App\Http\Controllers\V1\Auth\MeController::class,"update"])->middleware("auth:sanctum");
Route::post('/logout', [\App\Http\Controllers\V1\Auth\MeController::class,"logout"])->middleware("auth:sanctum");


Route::post('/login', [\App\Http\Controllers\V1\Auth\LoginController::class,"login"]);

/****************** فلوی جدید ورود/ثبت‌نام یکپارچه (کد یکبار مصرف) ******************/
// مرحله ۱: بررسی شماره موبایل (کاربر وجود دارد؟ رمز دارد؟)
Route::post('/check', [\App\Http\Controllers\V1\Auth\OtpAuthController::class,"check"]);
// ارسال کد یکبار مصرف (هم برای ورود کاربر موجود، هم ثبت‌نام کاربر جدید)
Route::post('/otp/send', [\App\Http\Controllers\V1\Auth\OtpAuthController::class,"sendCode"]);
// تایید کد و ورود/ثبت‌نام و دریافت توکن
Route::post('/otp/verify', [\App\Http\Controllers\V1\Auth\OtpAuthController::class,"verifyCode"]);
// تعیین/تغییر رمز عبور توسط کاربر لاگین‌شده (مخصوص کاربری که با کد ثبت‌نام کرده و می‌خواهد رمز بگذارد)
Route::post('/set_password', [\App\Http\Controllers\V1\Auth\OtpAuthController::class,"setPassword"])->middleware("auth:sanctum");

Route::post('/register/send_code', [\App\Http\Controllers\V1\Auth\RegisterController::class,"sendVerificationCode"]);
Route::post('/register/verify_code', [\App\Http\Controllers\V1\Auth\RegisterController::class,"verifyCode"]);
Route::post('/register', [\App\Http\Controllers\V1\Auth\RegisterController::class,"register"]);

Route::post('/reset_password/send_code', [\App\Http\Controllers\V1\Auth\ResetPasswordController::class,"sendVerificationCode"]);
Route::post('/reset_password/verify_code', [\App\Http\Controllers\V1\Auth\ResetPasswordController::class,"verifyCode"]);
Route::post('/reset_password', [\App\Http\Controllers\V1\Auth\ResetPasswordController::class,"reset"]);

Route::post('/change_password', [\App\Http\Controllers\V1\Auth\ResetPasswordController::class,"reset"]);
