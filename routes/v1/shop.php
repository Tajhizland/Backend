<?php

use App\Http\Controllers\V1\Shop\CartController;
use App\Http\Controllers\V1\Shop\CategoryController;
use App\Http\Controllers\V1\Shop\FavoriteController;
use App\Http\Controllers\V1\Shop\HomePageController;
use App\Http\Controllers\V1\Shop\NewsController;
use App\Http\Controllers\V1\Shop\ProductController;
use App\Http\Controllers\V1\Shop\SearchController;
use Illuminate\Support\Facades\Route;


Route::get('/homepage', [HomePageController::class, "index"]);
Route::get('/menu', [\App\Http\Controllers\V1\Shop\MenuController::class, "get"]);
Route::get('/d', [\App\Http\Controllers\V1\Admin\DashboardController::class, "index"]);
Route::get('city/get/{id}', [\App\Http\Controllers\V1\Shop\AddressController::class, "getCities"]);
Route::get('province/get', [\App\Http\Controllers\V1\Shop\AddressController::class, "getProvinces"]);
Route::post('contact', [\App\Http\Controllers\V1\Shop\ContactController::class, "store"]);
Route::get('my-orders', [\App\Http\Controllers\V1\Shop\OrderController::class, "userOrderPaginate"])->middleware("auth:sanctum");
//Route::get("test", function (\App\Services\ProductImage\ProductImageService $productImage) {
//    $images = \App\Models\ProductImage2::where("set", 0)->get();
//    foreach ($images as $item) {
//        $response = Http::get("https://tajhizland.com/upload/$item->url");
//        if ($response->successful()) {
//            $imageContent = $response->body();
//            $productImage->upload2($item->product_id, $imageContent);
//            \App\Models\ProductImage2::where("id", $item->id)->update(["set" => 1]);
//        } else {
//            dd($item);
//        }
//    }
//})->withoutMiddleware(\App\Http\Middleware\TransactionMiddleware::class);
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
Route::group(["prefix" => "search"], function () {
    Route::post('/', [SearchController::class, "index"]);
    Route::post('/paginate', [SearchController::class, "paginate"]);
});

Route::group(["prefix" => "product"], function () {
    Route::post('find', [ProductController::class, "find"])->withoutMiddleware(\App\Http\Middleware\Fa2EnMiddleware::class);
});

Route::group(["prefix" => "category"], function () {
    Route::post('find', [CategoryController::class, "index"])->withoutMiddleware(\App\Http\Middleware\Fa2EnMiddleware::class);
});
Route::group(["prefix" => "brand"], function () {
    Route::post('find', [\App\Http\Controllers\V1\Shop\BrandController::class, "index"])->withoutMiddleware(\App\Http\Middleware\Fa2EnMiddleware::class);
});

Route::group(["prefix" => "news"], function () {
    Route::post('find', [NewsController::class, "findByUrl"])->withoutMiddleware(\App\Http\Middleware\Fa2EnMiddleware::class);
    Route::get('paginated', [NewsController::class, "paginate"]);
});

Route::group(["prefix" => "page"], function () {
    Route::post('find', [\App\Http\Controllers\V1\Shop\PageController::class, "findByUrl"])->withoutMiddleware(\App\Http\Middleware\Fa2EnMiddleware::class);
});

Route::group(["prefix" => "faq"], function () {
    Route::post('get', [\App\Http\Controllers\V1\Shop\FaqController::class, "getActive"]);
});

Route::group(["prefix" => "address", "middleware" => "auth:sanctum"], function () {
    Route::post('update', [\App\Http\Controllers\V1\Shop\AddressController::class, "createOrUpdate"]);
    Route::get('find', [\App\Http\Controllers\V1\Shop\AddressController::class, "find"]);
});

Route::group(["prefix" => "delivery"], function () {
    Route::get('get', [\App\Http\Controllers\V1\Shop\DeliveryController::class, "getActives"]);
    Route::post('select', [\App\Http\Controllers\V1\Shop\DeliveryController::class, "select"])->middleware("auth:sanctum");
});

Route::group(["prefix" => "payment"], function () {
    Route::post('request', [\App\Http\Controllers\V1\Shop\PaymentController::class, "requestPayment"])->middleware("auth:sanctum");
    Route::get('verify', [\App\Http\Controllers\V1\Shop\PaymentController::class, "verifyPayment"]);
});

Route::group(["prefix" => "on-hold-order", "middleware" => "auth:sanctum"], function () {
    Route::get('get', [\App\Http\Controllers\V1\Shop\OnHoldOrderController::class, "userHoldOnPaginate"]);
    Route::post('payment/{id}', [\App\Http\Controllers\V1\Shop\OnHoldOrderController::class, "payment"]);
});

Route::group(["prefix" => "comment"], function () {
    Route::post('submit', [\App\Http\Controllers\V1\Shop\CommentController::class, "store"])->middleware("auth:sanctum");
 });
