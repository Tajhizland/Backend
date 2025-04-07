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
Route::get('emalls/list', [\App\Http\Controllers\V1\Shop\EmallsController::class, "list"]);
Route::get('torob/list', [\App\Http\Controllers\V1\Shop\TorobController::class, "get"]);
Route::post('footprint', [\App\Http\Controllers\V1\Shop\FootprintController::class, "handle"]);

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
    Route::get('discount', [ProductController::class, "getDiscountedProducts"]);
});

Route::group(["prefix" => "category"], function () {
    Route::post('find', [CategoryController::class, "index"])->withoutMiddleware(\App\Http\Middleware\Fa2EnMiddleware::class);
});
Route::group(["prefix" => "brand"], function () {
    Route::post('find', [\App\Http\Controllers\V1\Shop\BrandController::class, "index"])->withoutMiddleware(\App\Http\Middleware\Fa2EnMiddleware::class);
    Route::get('list', [\App\Http\Controllers\V1\Shop\BrandController::class, "list"]);
});

Route::group(["prefix" => "news"], function () {
    Route::post('find', [NewsController::class, "findByUrl"])->withoutMiddleware(\App\Http\Middleware\Fa2EnMiddleware::class);
    Route::get('paginated', [NewsController::class, "paginate"]);
});

Route::group(["prefix" => "landing"], function () {
    Route::post('find', [\App\Http\Controllers\V1\Shop\LandingController::class, "findByUrl"])->withoutMiddleware(\App\Http\Middleware\Fa2EnMiddleware::class);
});

Route::group(["prefix" => "page"], function () {
    Route::post('find', [\App\Http\Controllers\V1\Shop\PageController::class, "findByUrl"])->withoutMiddleware(\App\Http\Middleware\Fa2EnMiddleware::class);
});

Route::group(["prefix" => "faq"], function () {
    Route::post('get', [\App\Http\Controllers\V1\Shop\FaqController::class, "getActive"]);
});

Route::group(["prefix" => "address", "middleware" => "auth:sanctum"], function () {
//    Route::post('update', [\App\Http\Controllers\V1\Shop\AddressController::class, "createOrUpdate"]);
    Route::post('update', [\App\Http\Controllers\V1\Shop\AddressController::class, "updateOrCreate"]);
    Route::get('findActive', [\App\Http\Controllers\V1\Shop\AddressController::class, "findActive"]);
    Route::get('all', [\App\Http\Controllers\V1\Shop\AddressController::class, "getAll"]);
    Route::post('active/change', [\App\Http\Controllers\V1\Shop\AddressController::class, "changeActive"]);
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

Route::group(["prefix" => "guaranty"], function () {
    Route::post('find', [\App\Http\Controllers\V1\Shop\GuarantyController::class, "findByUrl"])->withoutMiddleware(\App\Http\Middleware\Fa2EnMiddleware::class);
});

Route::group(["prefix" => "vlog"], function () {
    Route::post('find', [\App\Http\Controllers\V1\Shop\VlogController::class, "find"])->withoutMiddleware(\App\Http\Middleware\Fa2EnMiddleware::class);
    Route::get('listing', [\App\Http\Controllers\V1\Shop\VlogController::class, "listing"]);
});
Route::group(["prefix" => "vlog_category"], function () {
    Route::get("list", [\App\Http\Controllers\V1\Admin\VlogCategoryController::class, "list"]);
});
Route::group(["prefix" => "special"], function () {
    Route::get('list', [\App\Http\Controllers\V1\Shop\SpecialProductController::class, "list"]);
});
Route::group(["prefix" => "sample"], function () {
    Route::get('find', [\App\Http\Controllers\V1\Shop\SampleController::class, "index"]);
});
Route::group(["prefix" => "sitemap"], function () {
    Route::get('product', [\App\Http\Controllers\V1\Shop\SitemapController::class, "getProductSitemap"]);
    Route::get('category', [\App\Http\Controllers\V1\Shop\SitemapController::class, "getCategorySitemap"]);
    Route::get('brand', [\App\Http\Controllers\V1\Shop\SitemapController::class, "getBrandSitemap"]);
    Route::get('vlog', [\App\Http\Controllers\V1\Shop\SitemapController::class, "getVlogSitemap"]);
    Route::get('blog', [\App\Http\Controllers\V1\Shop\SitemapController::class, "getBlogSitemap"]);
    Route::get('guaranty', [\App\Http\Controllers\V1\Shop\SitemapController::class, "getGuarantySitemap"]);
    Route::get('landing', [\App\Http\Controllers\V1\Shop\SitemapController::class, "getLandingSitemap"]);
});
Route::get('info', function () {
    phpinfo();
});
Route::get('info2', function () {
    $news=\App\Models\Product::all();
    foreach ($news as $new)
    {
        $content=$new->review;
        $content=str_replace("http://tajhizland.com/upload/","https://c778665.parspack.net/upload/upload/",$content);
        $new->review=$content;
        $new->save();
    }
});



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
