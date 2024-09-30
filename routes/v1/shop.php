<?php

use App\Http\Controllers\V1\Shop\CartController;
use App\Http\Controllers\V1\Shop\CategoryController;
use App\Http\Controllers\V1\Shop\FavoriteController;
use App\Http\Controllers\V1\Shop\HomePageController;
use App\Http\Controllers\V1\Shop\NewsController;
use App\Http\Controllers\V1\Shop\ProductController;
use App\Http\Controllers\V1\Shop\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/search', [SearchController::class, "index"]);

Route::get('/homepage', [HomePageController::class, "index"]);
Route::get('/menu', [\App\Http\Controllers\V1\Shop\MenuController::class, "get"]);

Route::group(["prefix" => "cart", "middleware" => "auth:sanctum"], function () {
    Route::post('add-to-cart', [CartController::class, "addToCart"]);
    Route::post('remove-item', [CartController::class, "removeItem"]);
    Route::post('clear-all', [CartController::class, "clearAll"]);
    Route::post('increase', [CartController::class, "increase"]);
    Route::post('decrease', [CartController::class, "decrease"]);
    Route::get('get', [CartController::class, "get"]);
});

Route::group(["prefix" => "favorite", "middleware" => "auth:sanctum"], function () {
    Route::get('show', [FavoriteController::class, "index"]);
    Route::post('add-item', [FavoriteController::class, "addProduct"]);
    Route::post('remove-item', [FavoriteController::class, "removeProduct"]);
});

Route::group(["prefix" => "product"], function () {
    Route::post('find', [ProductController::class, "find"])->withoutMiddleware(\App\Http\Middleware\Fa2EnMiddleware::class);
});

Route::group(["prefix" => "category"], function () {
    Route::post('find', [CategoryController::class, "index"])->withoutMiddleware(\App\Http\Middleware\Fa2EnMiddleware::class);
});

Route::group(["prefix" => "news"], function () {
    Route::post('find', [NewsController::class, "findByUrl"])->withoutMiddleware(\App\Http\Middleware\Fa2EnMiddleware::class);
    Route::get('paginated', [NewsController::class, "paginate"]);
});
