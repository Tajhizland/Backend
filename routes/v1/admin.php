<?php

use Illuminate\Support\Facades\Route;

Route::group(["middleware" => "auth:sanctum"], function () {

    Route::get('/dashboard', [\App\Http\Controllers\V1\Admin\DashboardController::class, "index"]);

    Route::group(["prefix" => "notification"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\NotificationController::class, "dataTable"]);
        Route::get("unseen", [\App\Http\Controllers\V1\Admin\NotificationController::class, "unSeen"]);
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
        Route::post("filter/set", [\App\Http\Controllers\V1\Admin\ProductController::class, "setFilter"]);
        Route::post("option/set", [\App\Http\Controllers\V1\Admin\ProductController::class, "setOption"]);
        Route::post("color/set", [\App\Http\Controllers\V1\Admin\ProductController::class, "setColor"]);
        Route::post("color/fast-update", [\App\Http\Controllers\V1\Admin\ProductController::class, "colorFastUpdate"]);
        Route::post("image/set", [\App\Http\Controllers\V1\Admin\ProductController::class, "setImage"]);
        Route::post("video/set", [\App\Http\Controllers\V1\Admin\ProductController::class, "setVideo"]);
        Route::delete("image/delete/{id}", [\App\Http\Controllers\V1\Admin\ProductController::class, "removeImage"]);
    });
    Route::group(["prefix" => "category"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\CategoryController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\CategoryController::class, "findById"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\CategoryController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\CategoryController::class, "update"]);
        Route::get("list", [\App\Http\Controllers\V1\Admin\CategoryController::class, "list"]);
        Route::get("product/list/{id}", [\App\Http\Controllers\V1\Admin\CategoryController::class, "productList"]);
        Route::get("filter/get/{id}", [\App\Http\Controllers\V1\Admin\CategoryController::class, "getFilter"]);
        Route::get("option/get/{id}", [\App\Http\Controllers\V1\Admin\CategoryController::class, "getOption"]);
        Route::post("filter/set", [\App\Http\Controllers\V1\Admin\CategoryController::class, "setFilter"]);
        Route::post("option/set", [\App\Http\Controllers\V1\Admin\CategoryController::class, "setOption"]);
        Route::post("sort", [\App\Http\Controllers\V1\Admin\CategoryController::class, "productSort"]);
        Route::post("image/delete/{id}", [\App\Http\Controllers\V1\Admin\CategoryController::class, "deleteImage"]);
    });
    Route::group(["prefix" => "brand"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\BrandController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\BrandController::class, "findById"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\BrandController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\BrandController::class, "update"]);
        Route::post("sort", [\App\Http\Controllers\V1\Admin\BrandController::class, "sort"]);
        Route::get("list", [\App\Http\Controllers\V1\Admin\BrandController::class, "list"]);
    });
    Route::group(["prefix" => "news"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\NewsController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\NewsController::class, "findById"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\NewsController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\NewsController::class, "update"]);
    });
    Route::group(["prefix" => "guaranty"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\GuarantyController::class, "dataTable"]);
        Route::get("list", [\App\Http\Controllers\V1\Admin\GuarantyController::class, "list"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\GuarantyController::class, "findById"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\GuarantyController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\GuarantyController::class, "update"]);
    });

    Route::group(["prefix" => "blogCategory"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\BlogCategoryController::class, "dataTable"]);
        Route::get("list", [\App\Http\Controllers\V1\Admin\BlogCategoryController::class, "list"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\BlogCategoryController::class, "findById"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\BlogCategoryController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\BlogCategoryController::class, "update"]);
        Route::post("list", [\App\Http\Controllers\V1\Admin\BlogCategoryController::class, "list"]);
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
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\CommentController::class, "findById"]);
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
    Route::group(["prefix" => "slider"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\SliderController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\SliderController::class, "findById"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\SliderController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\SliderController::class, "update"]);
        Route::get("all_desktop", [\App\Http\Controllers\V1\Admin\SliderController::class, "getAllDesktop"]);
        Route::get("all_mobile", [\App\Http\Controllers\V1\Admin\SliderController::class, "getAllMobile"]);
        Route::post("sort", [\App\Http\Controllers\V1\Admin\SliderController::class, "sort"]);
    });
    Route::group(["prefix" => "special_product"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\SpecialProductController::class, "dataTable"]);
        Route::delete("delete/{id}", [\App\Http\Controllers\V1\Admin\SpecialProductController::class, "delete"]);
        Route::post("add", [\App\Http\Controllers\V1\Admin\SpecialProductController::class, "add"]);
        Route::post("homepage", [\App\Http\Controllers\V1\Admin\SpecialProductController::class, "homepage"]);
    });
    Route::group(["prefix" => "popular_category"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\PopularCategoryController::class, "dataTable"]);
        Route::delete("delete/{id}", [\App\Http\Controllers\V1\Admin\PopularCategoryController::class, "delete"]);
        Route::post("add", [\App\Http\Controllers\V1\Admin\PopularCategoryController::class, "add"]);
    });
    Route::group(["prefix" => "popular_product"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\PopularProductController::class, "dataTable"]);
        Route::delete("delete/{id}", [\App\Http\Controllers\V1\Admin\PopularProductController::class, "delete"]);
        Route::post("add", [\App\Http\Controllers\V1\Admin\PopularProductController::class, "add"]);
    });
    Route::group(["prefix" => "homepage_category"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\HomepageCategoryController::class, "dataTable"]);
        Route::delete("delete/{id}", [\App\Http\Controllers\V1\Admin\HomepageCategoryController::class, "delete"]);
        Route::post("add", [\App\Http\Controllers\V1\Admin\HomepageCategoryController::class, "add"]);
        Route::post("setIcon", [\App\Http\Controllers\V1\Admin\HomepageCategoryController::class, "setIcon"]);
    });
    Route::group(["prefix" => "menu"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\MenuController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\MenuController::class, "findById"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\MenuController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\MenuController::class, "update"]);
        Route::get("list", [\App\Http\Controllers\V1\Admin\MenuController::class, "list"]);
        Route::delete("delete/{id}", [\App\Http\Controllers\V1\Admin\MenuController::class, "delete"]);
        Route::delete("banner/delete/{id}", [\App\Http\Controllers\V1\Admin\MenuController::class, "deleteBanner"]);
    });
    Route::group(["prefix" => "concept"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\ConceptController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\ConceptController::class, "findById"]);
        Route::get("items/get/{id}", [\App\Http\Controllers\V1\Admin\ConceptController::class, "getItems"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\ConceptController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\ConceptController::class, "update"]);
        Route::post("items/set", [\App\Http\Controllers\V1\Admin\ConceptController::class, "setItem"]);
        Route::delete("items/delete/{id}", [\App\Http\Controllers\V1\Admin\ConceptController::class, "deleteItem"]);
        Route::get("items/{id}", [\App\Http\Controllers\V1\Admin\ConceptController::class, "getItems"]);
        Route::post("display", [\App\Http\Controllers\V1\Admin\ConceptController::class, "display"]);

    });
    Route::group(["prefix" => "search"], function () {
        Route::post("category", [\App\Http\Controllers\V1\Admin\SearchController::class, "searchCategory"]);
        Route::post("product", [\App\Http\Controllers\V1\Admin\SearchController::class, "searchProduct"]);
    });
    Route::group(["prefix" => "file"], function () {
        Route::post("upload", [\App\Http\Controllers\V1\Admin\FileManagerController::class, "upload"]);
        Route::post("get", [\App\Http\Controllers\V1\Admin\FileManagerController::class, "get"]);
        Route::delete("remove/{id}", [\App\Http\Controllers\V1\Admin\FileManagerController::class, "remove"]);
    });
    Route::group(["prefix" => "contact"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\ContactController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\ContactController::class, "find"]);
        Route::delete("delete/{id}", [\App\Http\Controllers\V1\Admin\ContactController::class, "remove"]);
    });
    Route::group(["prefix" => "page"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\PageController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\PageController::class, "findById"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\PageController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\PageController::class, "update"]);
    });
    Route::group(["prefix" => "faq"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\FaqController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\FaqController::class, "findById"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\FaqController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\FaqController::class, "update"]);
    });
    Route::group(["prefix" => "vlog"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\VlogController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\VlogController::class, "findById"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\VlogController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\VlogController::class, "update"]);
    });
    Route::group(["prefix" => "vlog_category"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\VlogCategoryController::class, "dataTable"]);
        Route::get("list", [\App\Http\Controllers\V1\Admin\VlogCategoryController::class, "list"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\VlogCategoryController::class, "findById"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\VlogCategoryController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\VlogCategoryController::class, "update"]);
    });
    Route::group(["prefix" => "banner"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\BannerController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\BannerController::class, "find"]);
        Route::delete("delete/{id}", [\App\Http\Controllers\V1\Admin\BannerController::class, "delete"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\BannerController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\BannerController::class, "update"]);
        Route::get("list", [\App\Http\Controllers\V1\Admin\BannerController::class, "list"]);
        Route::post("sort", [\App\Http\Controllers\V1\Admin\BannerController::class, "sort"]);
    });
    Route::group(["prefix" => "landing"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\LandingController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\LandingController::class, "findById"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\LandingController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\LandingController::class, "update"]);
        Route::post("product/set", [\App\Http\Controllers\V1\Admin\LandingController::class, "setProduct"]);
        Route::get("product/get/{id}", [\App\Http\Controllers\V1\Admin\LandingController::class, "getProduct"]);
        Route::delete("product/delete/{id}", [\App\Http\Controllers\V1\Admin\LandingController::class, "deleteProduct"]);
        Route::post("category/set", [\App\Http\Controllers\V1\Admin\LandingController::class, "setCategory"]);
        Route::get("category/get/{id}", [\App\Http\Controllers\V1\Admin\LandingController::class, "getCategory"]);
        Route::delete("category/delete/{id}", [\App\Http\Controllers\V1\Admin\LandingController::class, "deleteCategory"]);
        Route::post("banner/set", [\App\Http\Controllers\V1\Admin\LandingController::class, "setBanner"]);
        Route::get("banner/get/{id}", [\App\Http\Controllers\V1\Admin\LandingController::class, "getBanner"]);
        Route::delete("banner/delete/{id}", [\App\Http\Controllers\V1\Admin\LandingController::class, "deleteBanner"]);
    });
    Route::group(["prefix" => "poster"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\PosterController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\PosterController::class, "find"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\PosterController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\PosterController::class, "update"]);
    });
});
