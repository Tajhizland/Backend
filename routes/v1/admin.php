<?php

use Illuminate\Support\Facades\Route;

Route::group(["middleware" => "auth:sanctum"], function () {

    Route::group(["prefix" => "notification"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\NotificationController::class, "dataTable"]);
        Route::get("unseen", [\App\Http\Controllers\V1\Admin\NotificationController::class, "findById"]);
    });


    Route::group(["prefix" => "product"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\ProductController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\ProductController::class, "findById"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\ProductController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\ProductController::class, "update"]);

        Route::get("filter/get/{id}", [\App\Http\Controllers\V1\Admin\ProductController::class, "getFilter"]);
        Route::get("option/get/{id}", [\App\Http\Controllers\V1\Admin\ProductController::class, "getOption"]);
        Route::get("color/get/{id}", [\App\Http\Controllers\V1\Admin\ProductController::class, "getColor"]);
        Route::get("image/get/{id}", [\App\Http\Controllers\V1\Admin\ProductController::class, "getImage"]);
        Route::get("file/get/{id}", [\App\Http\Controllers\V1\Admin\ProductController::class, "getFiles"]);

        Route::post("filter/set", [\App\Http\Controllers\V1\Admin\ProductController::class, "setFilter"]);
        Route::post("option/set", [\App\Http\Controllers\V1\Admin\ProductController::class, "setOption"]);
        Route::post("color/set", [\App\Http\Controllers\V1\Admin\ProductController::class, "setColor"]);
        Route::post("image/set", [\App\Http\Controllers\V1\Admin\ProductController::class, "setImage"]);
        Route::post("file/set", [\App\Http\Controllers\V1\Admin\ProductController::class, "setFile"]);
    });

    Route::group(["prefix" => "category"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\CategoryController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\CategoryController::class, "findById"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\CategoryController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\CategoryController::class, "update"]);
        Route::get("list", [\App\Http\Controllers\V1\Admin\CategoryController::class, "list"]);
    });
    Route::group(["prefix" => "brand"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\BrandController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\BrandController::class, "findById"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\BrandController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\BrandController::class, "update"]);
        Route::get("list", [\App\Http\Controllers\V1\Admin\BrandController::class, "list"]);
    });
    Route::group(["prefix" => "news"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\NewsController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\NewsController::class, "findById"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\NewsController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\NewsController::class, "update"]);
    });

    Route::group(["prefix" => "option"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\OptionController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\OptionController::class, "findById"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\OptionController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\OptionController::class, "update"]);
    });
    Route::group(["prefix" => "user"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\UserController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\UserController::class, "findById"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\UserController::class, "update"]);
    });
    Route::group(["prefix" => "gateway"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\GatewayController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\GatewayController::class, "findById"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\GatewayController::class, "update"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\GatewayController::class, "store"]);
    });
    Route::group(["prefix" => "delivery"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\DeliveryController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\DeliveryController::class, "findById"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\DeliveryController::class, "update"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\DeliveryController::class, "store"]);
    });
    Route::group(["prefix" => "returned"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\ReturnedController::class, "dataTable"]);
        Route::post("accept", [\App\Http\Controllers\V1\Admin\ReturnedController::class, "accept"]);
        Route::post("reject", [\App\Http\Controllers\V1\Admin\ReturnedController::class, "reject"]);
    });
    Route::group(["prefix" => "comment"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\CommentController::class, "dataTable"]);
        Route::post("accept", [\App\Http\Controllers\V1\Admin\CommentController::class, "accept"]);
        Route::post("reject", [\App\Http\Controllers\V1\Admin\CommentController::class, "reject"]);
    });
    Route::group(["prefix" => "transaction"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\TransactionController::class, "dataTable"]);
    });
    Route::group(["prefix" => "order"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\OrderController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\OrderController::class, "findById"]);
        Route::post("update/status", [\App\Http\Controllers\V1\Admin\OrderController::class, "updateStatus"]);
    });
    Route::group(["prefix" => "onHoldOrder"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\OnHoldOrderController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\OnHoldOrderController::class, "findById"]);
        Route::post("accept", [\App\Http\Controllers\V1\Admin\OnHoldOrderController::class, "accept"]);
        Route::post("reject", [\App\Http\Controllers\V1\Admin\OnHoldOrderController::class, "reject"]);
    });
});
