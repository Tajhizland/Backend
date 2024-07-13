<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
     return $request;
 });

Route::get('/m',  [\App\Http\Controllers\V1\Auth\RegisterController::class,"sendVerificationCode"]);
