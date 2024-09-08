<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    return "Welcome Admin" . $request->get("id");
});
Route::get('/me', [\App\Http\Controllers\V1\Auth\MeController::class, "me"]);


Route::group(["prefix" => "product", "middleware" => "auth:sanctum"], function () {
    Route::get("dataTable", [\App\Http\Controllers\V1\Admin\ProductController::class, "dataTable"]);
    Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\ProductController::class, "findById"]);
    Route::post("store", [\App\Http\Controllers\V1\Admin\ProductController::class, "store"]);
    Route::post("update", [\App\Http\Controllers\V1\Admin\ProductController::class, "update"]);
});

Route::group(["prefix" => "category", "middleware" => "auth:sanctum"], function () {
    Route::get("dataTable", [\App\Http\Controllers\V1\Admin\CategoryController::class, "dataTable"]);
    Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\CategoryController::class, "findById"]);
    Route::post("store", [\App\Http\Controllers\V1\Admin\CategoryController::class, "store"]);
    Route::post("update", [\App\Http\Controllers\V1\Admin\CategoryController::class, "update"]);
    Route::get("list", [\App\Http\Controllers\V1\Admin\CategoryController::class, "list"]);
});
Route::group(["prefix" => "brand", "middleware" => "auth:sanctum"], function () {
    Route::get("dataTable", [\App\Http\Controllers\V1\Admin\BrandController::class, "dataTable"]);
    Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\BrandController::class, "findById"]);
    Route::post("store", [\App\Http\Controllers\V1\Admin\BrandController::class, "store"]);
    Route::post("update", [\App\Http\Controllers\V1\Admin\BrandController::class, "update"]);
    Route::get("list", [\App\Http\Controllers\V1\Admin\BrandController::class, "list"]);
});
Route::group(["prefix" => "news", "middleware" => "auth:sanctum"], function () {
    Route::get("dataTable", [\App\Http\Controllers\V1\Admin\NewsController::class, "dataTable"]);
    Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\NewsController::class, "findById"]);
    Route::post("store", [\App\Http\Controllers\V1\Admin\NewsController::class, "store"]);
    Route::post("update", [\App\Http\Controllers\V1\Admin\NewsController::class, "update"]);
});

Route::group(["prefix" => "option", "middleware" => "auth:sanctum"], function () {
    Route::get("dataTable", [\App\Http\Controllers\V1\Admin\OptionController::class, "dataTable"]);
    Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\OptionController::class, "findById"]);
    Route::post("store", [\App\Http\Controllers\V1\Admin\OptionController::class, "store"]);
    Route::post("update", [\App\Http\Controllers\V1\Admin\OptionController::class, "update"]);
});
Route::group(["prefix" => "user", "middleware" => "auth:sanctum"], function () {
    Route::get("dataTable", [\App\Http\Controllers\V1\Admin\UserController::class, "dataTable"]);
    Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\UserController::class, "findById"]);
    Route::post("update", [\App\Http\Controllers\V1\Admin\UserController::class, "update"]);
});
Route::group(["prefix" => "gateway", "middleware" => "auth:sanctum"], function () {
    Route::get("dataTable", [\App\Http\Controllers\V1\Admin\GatewayController::class, "dataTable"]);
    Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\GatewayController::class, "findById"]);
    Route::post("update", [\App\Http\Controllers\V1\Admin\GatewayController::class, "update"]);
    Route::post("store", [\App\Http\Controllers\V1\Admin\GatewayController::class, "store"]);
});
Route::group(["prefix" => "delivery", "middleware" => "auth:sanctum"], function () {
    Route::get("dataTable", [\App\Http\Controllers\V1\Admin\DeliveryController::class, "dataTable"]);
    Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\DeliveryController::class, "findById"]);
    Route::post("update", [\App\Http\Controllers\V1\Admin\DeliveryController::class, "update"]);
    Route::post("store", [\App\Http\Controllers\V1\Admin\DeliveryController::class, "store"]);
});
Route::group(["prefix" => "returned", "middleware" => "auth:sanctum"], function () {
    Route::get("dataTable", [\App\Http\Controllers\V1\Admin\ReturnedController::class, "dataTable"]);
    Route::post("accept", [\App\Http\Controllers\V1\Admin\ReturnedController::class, "accept"]);
    Route::post("reject", [\App\Http\Controllers\V1\Admin\ReturnedController::class, "reject"]);
});
Route::group(["prefix" => "comment", "middleware" => "auth:sanctum"], function () {
    Route::get("dataTable", [\App\Http\Controllers\V1\Admin\CommentController::class, "dataTable"]);
    Route::post("accept", [\App\Http\Controllers\V1\Admin\CommentController::class, "accept"]);
    Route::post("reject", [\App\Http\Controllers\V1\Admin\CommentController::class, "reject"]);
});
Route::group(["prefix" => "transaction", "middleware" => "auth:sanctum"], function () {
    Route::get("dataTable", [\App\Http\Controllers\V1\Admin\TransactionController::class, "dataTable"]);
});
Route::group(["prefix" => "order", "middleware" => "auth:sanctum"], function () {
    Route::get("dataTable", [\App\Http\Controllers\V1\Admin\OrderController::class, "dataTable"]);
    Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\OrderController::class, "findById"]);
    Route::post("update/status", [\App\Http\Controllers\V1\Admin\OrderController::class, "updateStatus"]);
});
