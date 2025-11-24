<?php

use Illuminate\Support\Facades\Route;

Route::group(["middleware" => "auth:sanctum"], function () {

    Route::get('/dashboard', [\App\Http\Controllers\V1\Admin\DashboardController::class, "index"]);

    Route::group(["prefix" => "notification"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\NotificationController::class, "dataTable"]);
        Route::get("unseen", [\App\Http\Controllers\V1\Admin\NotificationController::class, "unSeen"]);
        Route::post("seen", [\App\Http\Controllers\V1\Admin\NotificationController::class, "seen"]);
    });
    Route::group(["prefix" => "product"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\ProductController::class, "dataTable"]);
        Route::get("stock/dataTable", [\App\Http\Controllers\V1\Admin\ProductController::class, "stockProductDataTable"]);
        Route::get("has-discount-dataTable", [\App\Http\Controllers\V1\Admin\ProductController::class, "hasDiscountDataTable"]);
        Route::get("has-limit-dataTable", [\App\Http\Controllers\V1\Admin\ProductController::class, "hasLimitDataTable"]);
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
        Route::post("image/sort", [\App\Http\Controllers\V1\Admin\ProductController::class, "sortImage"]);
        Route::post("video/set", [\App\Http\Controllers\V1\Admin\ProductController::class, "setVideo"]);
        Route::post("video/set2", [\App\Http\Controllers\V1\Admin\ProductController::class, "setVideo2"]);
        Route::get("video/get/{id}", [\App\Http\Controllers\V1\Admin\ProductController::class, "getVideo"]);
        Route::delete("video/delete/{id}", [\App\Http\Controllers\V1\Admin\ProductController::class, "deleteVideo"]);
        Route::delete("image/delete/{id}", [\App\Http\Controllers\V1\Admin\ProductController::class, "removeImage"]);
        Route::post("option/update-product-option", [\App\Http\Controllers\V1\Admin\ProductController::class, "updateProductOption"]);
        Route::post("search-list", [\App\Http\Controllers\V1\Admin\ProductController::class, "searchList"]);
        Route::post("group-change", [\App\Http\Controllers\V1\Admin\ProductController::class, "groupChange"]);
    });
    Route::group(["prefix" => "category"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\CategoryController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\CategoryController::class, "findById"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\CategoryController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\CategoryController::class, "update"]);
        Route::get("list", [\App\Http\Controllers\V1\Admin\CategoryController::class, "list"]);
        Route::get("option-item/{id}", [\App\Http\Controllers\V1\Admin\CategoryController::class, "getOptionItem"]);
        Route::get("product/list/{id}", [\App\Http\Controllers\V1\Admin\CategoryController::class, "productList"]);
        Route::get("filter/get/{id}", [\App\Http\Controllers\V1\Admin\CategoryController::class, "getFilter"]);
        Route::get("option/get/{id}", [\App\Http\Controllers\V1\Admin\CategoryController::class, "getOption"]);
        Route::post("filter/set", [\App\Http\Controllers\V1\Admin\CategoryController::class, "setFilter"]);
        Route::post("option/set", [\App\Http\Controllers\V1\Admin\CategoryController::class, "setOption"]);
        Route::post("option/sort", [\App\Http\Controllers\V1\Admin\CategoryController::class, "sortOption"]);
        Route::post("option-item/sort", [\App\Http\Controllers\V1\Admin\CategoryController::class, "sortOptionItem"]);
        Route::post("sort", [\App\Http\Controllers\V1\Admin\CategoryController::class, "productSort"]);
        Route::post("image/delete/{id}", [\App\Http\Controllers\V1\Admin\CategoryController::class, "deleteImage"]);

        Route::post("option/update", [\App\Http\Controllers\V1\Admin\CategoryController::class, "updateOption"]);

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
        Route::post("type", [\App\Http\Controllers\V1\Admin\UserController::class, "getByType"]);
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\UserController::class, "dataTable"]);
        Route::get("admin/dataTable", [\App\Http\Controllers\V1\Admin\UserController::class, "adminDataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\UserController::class, "findById"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\UserController::class, "update"]);
        Route::post("wallet", [\App\Http\Controllers\V1\Admin\UserController::class, "updateWallet"]);

        Route::get('address/{id}', [\App\Http\Controllers\V1\Admin\UserController::class, "getAddress"]);
        Route::post('address/update', [\App\Http\Controllers\V1\Admin\UserController::class, "updateOrCreateAddress"]);
        Route::post('address/active/change', [\App\Http\Controllers\V1\Admin\UserController::class, "changeActiveAddress"]);

        Route::get('on-hold-order/{id}', [\App\Http\Controllers\V1\Admin\UserController::class, "getOnHoldOrder"]);
        Route::get('order/{id}', [\App\Http\Controllers\V1\Admin\UserController::class, "getOrder"]);
        Route::get('login/{id}', [\App\Http\Controllers\V1\Admin\UserController::class, "loginUser"]);

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
        Route::delete("delete/{id}", [\App\Http\Controllers\V1\Admin\SliderController::class, "delete"]);
    });
    Route::group(["prefix" => "special_product"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\SpecialProductController::class, "dataTable"]);
        Route::delete("delete/{id}", [\App\Http\Controllers\V1\Admin\SpecialProductController::class, "delete"]);
        Route::post("add", [\App\Http\Controllers\V1\Admin\SpecialProductController::class, "add"]);
        Route::post("homepage", [\App\Http\Controllers\V1\Admin\SpecialProductController::class, "homepage"]);
        Route::get('list', [\App\Http\Controllers\V1\Admin\SpecialProductController::class, "list"]);
        Route::post("sort", [\App\Http\Controllers\V1\Admin\SpecialProductController::class, "sort"]);

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
        Route::post("search", [\App\Http\Controllers\V1\Admin\VlogController::class, "search"]);
        Route::get("list", [\App\Http\Controllers\V1\Admin\VlogController::class, "list"]);
        Route::post("sort", [\App\Http\Controllers\V1\Admin\VlogController::class, "sort"]);
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

    Route::group(["prefix" => "sample"], function () {
        Route::get("find", [\App\Http\Controllers\V1\Admin\SampleController::class, "find"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\SampleController::class, "update"]);

        Route::get("image/get", [\App\Http\Controllers\V1\Admin\SampleController::class, "getImages"]);
        Route::post("image/upload", [\App\Http\Controllers\V1\Admin\SampleController::class, "uploadImage"]);
        Route::post("image/sort", [\App\Http\Controllers\V1\Admin\SampleController::class, "sortImage"]);
        Route::delete("image/delete/{id}", [\App\Http\Controllers\V1\Admin\SampleController::class, "removeImage"]);

        Route::get("video/get", [\App\Http\Controllers\V1\Admin\SampleController::class, "getVideos"]);
        Route::post("video/add", [\App\Http\Controllers\V1\Admin\SampleController::class, "addVideo"]);
        Route::post("video/sort", [\App\Http\Controllers\V1\Admin\SampleController::class, "sortVideo"]);
        Route::delete("video/delete/{id}", [\App\Http\Controllers\V1\Admin\SampleController::class, "deleteVideo"]);
    });

    Route::group(["prefix" => "homepage_vlog"], function () {
        Route::get("get", [\App\Http\Controllers\V1\Admin\HomepageVlogController::class, "get"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\HomepageVlogController::class, "update"]);
    });
    Route::group(["prefix" => "wallet-transaction"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\WalletTransactionController::class, "dataTable"]);
    });

    Route::group(["prefix" => "trusted-brand"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\TrustedBrandController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\TrustedBrandController::class, "find"]);
        Route::delete("delete/{id}", [\App\Http\Controllers\V1\Admin\TrustedBrandController::class, "delete"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\TrustedBrandController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\TrustedBrandController::class, "update"]);
    });
    Route::group(["prefix" => "group"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\ProductGroupController::class, "dataTable"]);
        Route::get("field/{id}", [\App\Http\Controllers\V1\Admin\ProductGroupController::class, "getField"]);
        Route::get("product/{id}", [\App\Http\Controllers\V1\Admin\ProductGroupController::class, "getProduct"]);
        Route::get("field-value/{id}", [\App\Http\Controllers\V1\Admin\ProductGroupController::class, "getFieldValue"]);
        Route::delete("field/{id}", [\App\Http\Controllers\V1\Admin\ProductGroupController::class, "removeField"]);
        Route::delete("product/{id}", [\App\Http\Controllers\V1\Admin\ProductGroupController::class, "removeProduct"]);
        Route::post("field", [\App\Http\Controllers\V1\Admin\ProductGroupController::class, "addField"]);
        Route::post("product", [\App\Http\Controllers\V1\Admin\ProductGroupController::class, "addProduct"]);
        Route::post("set", [\App\Http\Controllers\V1\Admin\ProductGroupController::class, "set"]);
    });

    Route::group(["prefix" => "run-concept-answer"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\RunConceptAnswerController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\RunConceptAnswerController::class, "find"]);
        Route::get("question/{id}", [\App\Http\Controllers\V1\Admin\RunConceptAnswerController::class, "getByQuestionId"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\RunConceptAnswerController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\RunConceptAnswerController::class, "update"]);
    });

    Route::group(["prefix" => "run-concept-question"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\RunConceptQuestionController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\RunConceptQuestionController::class, "find"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\RunConceptQuestionController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\RunConceptQuestionController::class, "update"]);
        Route::get("list", [\App\Http\Controllers\V1\Admin\RunConceptQuestionController::class, "list"]);
    });

    Route::group(["prefix" => "dictionary"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\DictionaryController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\DictionaryController::class, "find"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\DictionaryController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\DictionaryController::class, "update"]);
        Route::delete("delete/{id}", [\App\Http\Controllers\V1\Admin\DictionaryController::class, "remove"]);
    });
    Route::group(["prefix" => "sms"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\SmsController::class, "dataTable"]);
        Route::get("item/{id}", [\App\Http\Controllers\V1\Admin\SmsController::class, "itemDataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\SmsController::class, "viewItem"]);
        Route::post("send", [\App\Http\Controllers\V1\Admin\SmsController::class, "send"]);
        Route::post("send-to-contact", [\App\Http\Controllers\V1\Admin\SmsController::class, "sendToContact"]);
    });
    Route::group(["prefix" => "permission"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\PermissionController::class, "dataTable"]);
        Route::get("list", [\App\Http\Controllers\V1\Admin\PermissionController::class, "getAll"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\PermissionController::class, "find"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\PermissionController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\PermissionController::class, "update"]);
    });
    Route::group(["prefix" => "role"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\RoleController::class, "dataTable"]);
        Route::get("list", [\App\Http\Controllers\V1\Admin\RoleController::class, "getAll"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\RoleController::class, "find"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\RoleController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\RoleController::class, "update"]);
    });
    Route::group(["prefix" => "phone-bock"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\PhoneBockController::class, "dataTable"]);
        Route::get("all", [\App\Http\Controllers\V1\Admin\PhoneBockController::class, "all"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\PhoneBockController::class, "find"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\PhoneBockController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\PhoneBockController::class, "update"]);
        Route::post("excel", [\App\Http\Controllers\V1\Admin\PhoneBockController::class, "uploadExcel"]);
    });

    Route::group(["prefix" => "cast"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\CastController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\CastController::class, "find"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\CastController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\CastController::class, "update"]);
    });

    Route::group(["prefix" => "campaign"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\CampaignController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\CampaignController::class, "find"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\CampaignController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\CampaignController::class, "update"]);
    });

    Route::group(["prefix" => "discount"], function () {
        Route::get("dataTable", [\App\Http\Controllers\V1\Admin\DiscountController::class, "dataTable"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\DiscountController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\DiscountController::class, "update"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\DiscountController::class, "find"]);
    });

    Route::group(["prefix" => "campaign-slider"], function () {
        Route::get("dataTable/{id}", [\App\Http\Controllers\V1\Admin\CampaignSliderController::class, "campaignDataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\CampaignSliderController::class, "find"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\CampaignSliderController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\CampaignSliderController::class, "update"]);
        Route::get("all_desktop", [\App\Http\Controllers\V1\Admin\CampaignSliderController::class, "getAllDesktop"]);
        Route::get("all_mobile", [\App\Http\Controllers\V1\Admin\CampaignSliderController::class, "getAllMobile"]);
        Route::post("sort", [\App\Http\Controllers\V1\Admin\CampaignSliderController::class, "sort"]);
        Route::delete("delete/{id}", [\App\Http\Controllers\V1\Admin\CampaignSliderController::class, "delete"]);
    });

    Route::group(["prefix" => "campaign-banner"], function () {
        Route::get("dataTable/{id}", [\App\Http\Controllers\V1\Admin\CampaignBannerController::class, "dataTable"]);
        Route::get("find/{id}", [\App\Http\Controllers\V1\Admin\CampaignBannerController::class, "find"]);
        Route::delete("delete/{id}", [\App\Http\Controllers\V1\Admin\CampaignBannerController::class, "delete"]);
        Route::post("store", [\App\Http\Controllers\V1\Admin\CampaignBannerController::class, "store"]);
        Route::post("update", [\App\Http\Controllers\V1\Admin\CampaignBannerController::class, "update"]);
        Route::get("list/{type}", [\App\Http\Controllers\V1\Admin\CampaignBannerController::class, "list"]);
        Route::post("sort", [\App\Http\Controllers\V1\Admin\CampaignBannerController::class, "sort"]);
    });

});
