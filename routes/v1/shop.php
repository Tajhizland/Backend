<?php

use App\Http\Controllers\V1\Shop\CartController;
use App\Http\Controllers\V1\Shop\CategoryController;
use App\Http\Controllers\V1\Shop\FavoriteController;
use App\Http\Controllers\V1\Shop\HomePageController;
use App\Http\Controllers\V1\Shop\ProductController;
use App\Http\Controllers\V1\Shop\SearchController;
use App\Http\Controllers\V1\Shop\NewsController;
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

Route::group(["prefix" => "favorite", "middleware" => "auth:sanctum"], function () {
    Route::get('show', [FavoriteController::class, "index"]);
    Route::get('add-item', [FavoriteController::class, "addProduct"]);
    Route::get('remove-item', [FavoriteController::class, "removeProduct"]);
});

Route::group(["prefix" => "product"], function () {

    Route::get('find/{url}', [ProductController::class, "find"])
        ->middleware("auth:sanctum")
        ->where('url', '.*');

});

Route::group(["prefix" => "category"], function () {
    Route::get('find', [CategoryController::class, "index"]);
});

Route::group(["prefix" => "news"], function () {
    Route::get('find/{url}', [NewsController::class, "findByUrl"])->where('url', '.*');;
    Route::get('paginated', [NewsController::class, "paginate"]);
});
