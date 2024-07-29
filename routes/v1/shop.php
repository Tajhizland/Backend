<?php

use App\Http\Controllers\V1\Shop\CartController;
use App\Http\Controllers\V1\Shop\HomePageController;
use App\Http\Controllers\V1\Shop\SearchController;
use App\Http\Controllers\V1\Shop\ProductController;
use App\Http\Controllers\V1\Shop\CategoryController;
use Illuminate\Support\Facades\Route;


Route::get('/search', [SearchController::class, "index"]);

Route::get('/homepage', [HomePageController::class, "index"]);

Route::group(["prefix" => "cart", "middleware" => "auth:sanctum"], function () {
    Route::get('add-to-cart', [CartController::class, "addToCart"]);
    Route::get('remove-item', [CartController::class, "removeItem"]);
    Route::get('clear-all', [CartController::class, "clearAll"]);
    Route::get('increase', [CartController::class, "increase"]);
    Route::get('decrease', [CartController::class, "decrease"]);
});

Route::group(["prefix" => "product"], function () {
    Route::get('find', [ProductController::class, "find"]);
});

Route::group(["prefix" => "category"], function () {
    Route::get('find', [CategoryController::class, "find"]);
});
