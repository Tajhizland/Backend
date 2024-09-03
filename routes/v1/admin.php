<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
   return "Welcome Admin".$request->get("id");
});
Route::get('/me', [\App\Http\Controllers\V1\Auth\MeController::class,"me"]);


Route::group(["prefix" => "product", "middleware" => "auth:sanctum"], function () {
    Route::get("dataTable",[\App\Http\Controllers\V1\Admin\ProductController::class ,"dataTable"]);
    Route::get("find/{id}",[\App\Http\Controllers\V1\Admin\ProductController::class ,"findById"]);
    Route::post("store",[\App\Http\Controllers\V1\Admin\ProductController::class ,"store"]);
    Route::post("update",[\App\Http\Controllers\V1\Admin\ProductController::class ,"update"]);
});

Route::group(["prefix" => "category", "middleware" => "auth:sanctum"], function () {
    Route::get("dataTable",[\App\Http\Controllers\V1\Admin\CategoryController::class ,"dataTable"]);
    Route::get("find/{id}",[\App\Http\Controllers\V1\Admin\CategoryController::class ,"findById"]);
    Route::post("store",[\App\Http\Controllers\V1\Admin\CategoryController::class ,"store"]);
    Route::post("update",[\App\Http\Controllers\V1\Admin\CategoryController::class ,"update"]);
});
