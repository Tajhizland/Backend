<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    $mv = \App\Models\MobileVerification::first();
    return new \App\Http\Resources\MobileVerificationResource($mv);
    return $mv->status->label();;
    return \App\Enums\MobileVerificationStatus::Pending->value;

    return $request;
});

Route::get('/stat', [\App\Http\Controllers\V1\Admin\OrderController::class, "updateStatus"]);

Route::get('/f/{url}', [\App\Http\Controllers\V1\Shop\ProductController::class, "find"])
    ->middleware(\App\Http\Middleware\TestMiddleware::class)->where('url', '.*');


Route::get('/ad/{id}', [\App\Http\Controllers\V1\Shop\AddressController::class, "find"]);
Route::get('/g',function (){
    $zibal=new \App\Services\Payment\Gateways\Zibal\ZibalService();
   return $zibal->request(123456789,10);
});
Route::get('/a', [\App\Http\Controllers\V1\Admin\ProductController::class, "update"]);

Route::get('/n', [\App\Http\Controllers\V1\Shop\NewsController::class, "paginate"]);
Route::get('/s', [\App\Http\Controllers\V1\Shop\CategoryController::class, "index"]);
Route::get('/c', [\App\Http\Controllers\V1\Shop\CartController::class, "get"]);
Route::get('/c2', [\App\Http\Controllers\V1\Admin\ProductController::class, "getPaginated"]);

Route::get('/m', [\App\Http\Controllers\V1\Auth\RegisterController::class, "sendVerificationCode"]);
Route::get('/l', [\App\Http\Controllers\V1\Auth\LoginController::class, "login"])->withoutMiddleware(\App\Http\Middleware\TransactionMiddleware::class);
Route::get('/me', [\App\Http\Controllers\V1\Auth\MeController::class, "me"])->middleware(["auth:sanctum"]);
Route::get('/url', [\App\Http\Controllers\V1\Shop\ProductController::class, "find"]);
