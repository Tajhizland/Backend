<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
   return "Welcome Admin".$request->get("id");
});
Route::get('/me', [\App\Http\Controllers\V1\Auth\MeController::class,"me"]);
