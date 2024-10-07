<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    return "Welcome Auth";
});

Route::get('/me', [\App\Http\Controllers\V1\Auth\MeController::class,"me"])->middleware("auth:sanctum");
Route::get('/profile/update', [\App\Http\Controllers\V1\Auth\MeController::class,"update"])->middleware("auth:sanctum");

Route::post('/login', [\App\Http\Controllers\V1\Auth\LoginController::class,"login"]);

Route::post('/register/send_code', [\App\Http\Controllers\V1\Auth\RegisterController::class,"sendVerificationCode"]);
Route::post('/register/verify_code', [\App\Http\Controllers\V1\Auth\RegisterController::class,"verifyCode"]);
Route::post('/register', [\App\Http\Controllers\V1\Auth\RegisterController::class,"register"]);

Route::post('/reset_password/send_code', [\App\Http\Controllers\V1\Auth\ResetPasswordController::class,"sendVerificationCode"]);
Route::post('/reset_password/verify_code', [\App\Http\Controllers\V1\Auth\ResetPasswordController::class,"verifyCode"]);
Route::post('/reset_password', [\App\Http\Controllers\V1\Auth\ResetPasswordController::class,"reset"]);

Route::post('/change_password', [\App\Http\Controllers\V1\Auth\ResetPasswordController::class,"reset"]);
