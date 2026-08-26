<?php

use Illuminate\Support\Facades\Route;

Route::group(["middleware" => "auth:sanctum"], function () {

    Route::get('/dashboard', [\App\Http\Controllers\V1\Admin\DashboardController::class, "index"]);

    Route::prefix("notification")->controller(\App\Http\Controllers\V1\Admin\NotificationController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::get("unseen", "unSeen");
        Route::patch("seen", "seen");
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
        Route::post("image/set-color", [\App\Http\Controllers\V1\Admin\ProductController::class, "setImageColor"]);
        Route::post("video/set", [\App\Http\Controllers\V1\Admin\ProductController::class, "setVideo"]);
        Route::post("video/set2", [\App\Http\Controllers\V1\Admin\ProductController::class, "setVideo2"]);
        Route::get("video/get/{id}", [\App\Http\Controllers\V1\Admin\ProductController::class, "getVideo"]);
        Route::delete("video/delete/{id}", [\App\Http\Controllers\V1\Admin\ProductController::class, "deleteVideo"]);
        Route::delete("image/delete/{id}", [\App\Http\Controllers\V1\Admin\ProductController::class, "removeImage"]);
        Route::post("option/update-product-option", [\App\Http\Controllers\V1\Admin\ProductController::class, "updateProductOption"]);
        Route::post("search-list", [\App\Http\Controllers\V1\Admin\ProductController::class, "searchList"]);
        Route::post("group-change", [\App\Http\Controllers\V1\Admin\ProductController::class, "groupChange"]);
        Route::post("group-change-stock", [\App\Http\Controllers\V1\Admin\ProductController::class, "groupChangeStock"]);
        Route::post("group-change-status", [\App\Http\Controllers\V1\Admin\ProductController::class, "groupChangeStatus"]);
        Route::post("group-change-digipay", [\App\Http\Controllers\V1\Admin\ProductController::class, "groupChangeDigipay"]);
        Route::post("group-change-snappay", [\App\Http\Controllers\V1\Admin\ProductController::class, "groupChangeSnappay"]);
        Route::post("group-change-percent", [\App\Http\Controllers\V1\Admin\ProductController::class, "groupChangePercent"]);

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
    Route::prefix("brand")->controller(\App\Http\Controllers\V1\Admin\BrandController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::get("list", "list");
        Route::post("sort", "sort");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
    });
    Route::prefix("news")->controller(\App\Http\Controllers\V1\Admin\NewsController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
    });
    Route::prefix("guaranty")->controller(\App\Http\Controllers\V1\Admin\GuarantyController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::get("list", "list");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
    });

    Route::prefix("blog-category")->controller(\App\Http\Controllers\V1\Admin\BlogCategoryController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::get("list", "list");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
    });

    Route::prefix("option")->controller(\App\Http\Controllers\V1\Admin\OptionController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
    });
    Route::prefix("user")->controller(\App\Http\Controllers\V1\Admin\UserController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::get("admin/dataTable", "adminDataTable");
        Route::post("type", "getByType");
        Route::post("wallet", "updateWallet");

        Route::post("address", "updateOrCreateAddress");
        Route::patch("address/active", "changeActiveAddress");

        Route::get("{id}", "show");
        Route::put("{id}", "update");
        Route::get("{id}/address", "getAddress");
        Route::get("{id}/on-hold-order", "getOnHoldOrder");
        Route::get("{id}/order", "getOrder");
        Route::get("{id}/login", "loginUser");
    });
    Route::prefix("gateway")->controller(\App\Http\Controllers\V1\Admin\GatewayController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
    });
    Route::prefix("delivery")->controller(\App\Http\Controllers\V1\Admin\DeliveryController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
    });
    Route::prefix("returned")->controller(\App\Http\Controllers\V1\Admin\ReturnedController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::patch("{id}/accept", "accept");
        Route::patch("{id}/reject", "reject");
    });
    Route::prefix("comment")->controller(\App\Http\Controllers\V1\Admin\CommentController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::get("{id}", "show");
        Route::patch("{id}/accept", "accept");
        Route::patch("{id}/reject", "reject");
    });
    Route::prefix("transaction")->controller(\App\Http\Controllers\V1\Admin\TransactionController::class)->group(function () {
        Route::get("dataTable", "dataTable");
    });
    Route::prefix("order")->group(function () {
        Route::controller(\App\Http\Controllers\V1\Admin\OrderController::class)->group(function () {
            Route::get("dataTable", "dataTable");
            Route::post("digipay-calc", "digipayCalc");
            Route::patch("item/{id}", "updateItem");
            Route::delete("item/{id}", "deleteItem");
            Route::get("{id}", "show");
            Route::patch("{id}/status", "updateStatus");
            Route::patch("{id}/cancel", "cancel");
        });

        Route::post('{id}/tapin', [\App\Http\Controllers\V1\Admin\TapinController::class, "register"]);
    });
    Route::prefix("on-hold-order")->controller(\App\Http\Controllers\V1\Admin\OnHoldOrderController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::get("{id}", "show");
        Route::patch("{id}/accept", "accept");
        Route::patch("{id}/reject", "reject");
    });
    Route::prefix("slider")->controller(\App\Http\Controllers\V1\Admin\SliderController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::get("all-desktop", "getAllDesktop");
        Route::get("all-mobile", "getAllMobile");
        Route::post("sort", "sort");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
        Route::delete("{id}", "destroy");
    });
    Route::prefix("special-product")->controller(\App\Http\Controllers\V1\Admin\SpecialProductController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::get("list", "list");
        Route::post("sort", "sort");
        Route::post("/", "store");
        Route::patch("{id}/homepage", "homepage");
        Route::delete("{id}", "destroy");
    });
    Route::prefix("popular-category")->controller(\App\Http\Controllers\V1\Admin\PopularCategoryController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::post("/", "store");
        Route::delete("{id}", "destroy");
    });
    Route::prefix("popular-product")->controller(\App\Http\Controllers\V1\Admin\PopularProductController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::post("/", "store");
        Route::delete("{id}", "destroy");
    });
    Route::prefix("homepage-category")->controller(\App\Http\Controllers\V1\Admin\HomepageCategoryController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::post("/", "store");
        Route::post("{id}/icon", "setIcon");
        Route::delete("{id}", "destroy");
    });
    Route::prefix("menu")->controller(\App\Http\Controllers\V1\Admin\MenuController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::get("list", "list");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
        Route::delete("{id}", "destroy");
        Route::delete("{id}/banner", "destroyBanner");
    });
    Route::prefix("concept")->controller(\App\Http\Controllers\V1\Admin\ConceptController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::post("item", "setItem");
        Route::delete("item/{id}", "deleteItem");
        Route::patch("item/{id}/display", "setDisplay");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
        Route::get("{id}/item", "getItems");
    });
    Route::prefix("search")->controller(\App\Http\Controllers\V1\Admin\SearchController::class)->group(function () {
        Route::post("category", "searchCategory");
        Route::post("product", "searchProduct");
    });
    Route::prefix("file")->controller(\App\Http\Controllers\V1\Admin\FileManagerController::class)->group(function () {
        Route::post("search", "index");
        Route::post("/", "store");
        Route::delete("{id}", "destroy");
    });
    Route::prefix("contact")->controller(\App\Http\Controllers\V1\Admin\ContactController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::get("{id}", "show");
        Route::delete("{id}", "destroy");
    });
    Route::prefix("page")->controller(\App\Http\Controllers\V1\Admin\PageController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
    });
    Route::prefix("faq")->controller(\App\Http\Controllers\V1\Admin\FaqController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
    });
    /** آپلود مستقیم مرورگر به S3 — فقط امضا و تأیید، بدون عبور فایل از سرور */
    Route::prefix("upload")->controller(\App\Http\Controllers\V1\Admin\UploadController::class)->group(function () {
        Route::post("initiate", "initiate");
        Route::post("sign-parts", "signParts");
        Route::post("complete", "complete");
        Route::post("abort", "abort");
    });
    Route::prefix("vlog")->controller(\App\Http\Controllers\V1\Admin\VlogController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::get("list", "list");
        Route::post("search", "search");
        Route::post("sort", "sort");
        Route::post("direct", "storeDirect");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
        Route::get("{id}/video-status", "videoStatus");
    });
    Route::prefix("vlog-category")->controller(\App\Http\Controllers\V1\Admin\VlogCategoryController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::get("list", "list");
        Route::post("sort", "sort");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
    });
    Route::prefix("banner")->controller(\App\Http\Controllers\V1\Admin\BannerController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::get("list", "list");
        Route::post("sort", "sort");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
        Route::delete("{id}", "destroy");
    });
    Route::prefix("landing")->controller(\App\Http\Controllers\V1\Admin\LandingController::class)->group(function () {
        Route::get("dataTable", "dataTable");

        Route::post("product", "setProduct");
        Route::delete("product/{id}", "deleteProduct");
        Route::post("category", "setCategory");
        Route::delete("category/{id}", "deleteCategory");
        Route::post("banner", "setBanner");
        Route::delete("banner/{id}", "deleteBanner");

        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
        Route::get("{id}/product", "getProduct");
        Route::get("{id}/category", "getCategory");
        Route::get("{id}/banner", "getBanner");
    });

    Route::prefix("poster")->controller(\App\Http\Controllers\V1\Admin\PosterController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
    });

    Route::prefix("sample")->controller(\App\Http\Controllers\V1\Admin\SampleController::class)->group(function () {
        Route::get("/", "find");
        Route::put("/", "update");

        Route::get("image", "getImages");
        Route::post("image", "uploadImage");
        Route::post("image/sort", "sortImage");
        Route::delete("image/{id}", "removeImage");

        Route::get("video", "getVideos");
        Route::post("video", "addVideo");
        Route::post("video/sort", "sortVideo");
        Route::delete("video/{id}", "deleteVideo");
    });

    Route::prefix("homepage-vlog")->controller(\App\Http\Controllers\V1\Admin\HomepageVlogController::class)->group(function () {
        Route::get("/", "get");
        Route::put("{id}", "update");
    });
    Route::prefix("wallet-transaction")->controller(\App\Http\Controllers\V1\Admin\WalletTransactionController::class)->group(function () {
        Route::get("dataTable", "dataTable");
    });

    Route::prefix("trusted-brand")->controller(\App\Http\Controllers\V1\Admin\TrustedBrandController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
        Route::delete("{id}", "destroy");
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

    Route::prefix("run-concept-answer")->controller(\App\Http\Controllers\V1\Admin\RunConceptAnswerController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::get("question/{id}", "getByQuestionId");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
    });

    Route::prefix("run-concept-question")->controller(\App\Http\Controllers\V1\Admin\RunConceptQuestionController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::get("list", "list");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
    });

    Route::prefix("dictionary")->controller(\App\Http\Controllers\V1\Admin\DictionaryController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
        Route::delete("{id}", "destroy");
    });
    Route::prefix("sms")->controller(\App\Http\Controllers\V1\Admin\SmsController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::post("send", "send");
        Route::post("send-to-contact", "sendToContact");
        Route::get("item/{id}", "showItem");
        Route::get("{id}/item", "itemDataTable");
    });
    Route::prefix("permission")->controller(\App\Http\Controllers\V1\Admin\PermissionController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::get("list", "getAll");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
    });
    Route::prefix("role")->controller(\App\Http\Controllers\V1\Admin\RoleController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::get("list", "getAll");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
    });
    Route::prefix("phone-bock")->controller(\App\Http\Controllers\V1\Admin\PhoneBockController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::get("all", "all");
        Route::post("excel", "uploadExcel");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
    });

    Route::prefix("cast")->controller(\App\Http\Controllers\V1\Admin\CastController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
    });

    Route::prefix("campaign")->controller(\App\Http\Controllers\V1\Admin\CampaignController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
    });

    Route::prefix("discount")->controller(\App\Http\Controllers\V1\Admin\DiscountController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::post("item", "setItem");
        Route::put("item", "updateItem");
        Route::post("top-item/sort", "sort");
        Route::get("top-item/{id}", "getTopDiscountItem");
        Route::delete("item/{id}", "deleteItem");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
        Route::get("{id}/item", "getItem");
    });

    Route::prefix("campaign-slider")->controller(\App\Http\Controllers\V1\Admin\CampaignSliderController::class)->group(function () {
        Route::get("dataTable/{id}", "campaignDataTable");
        Route::get("all-desktop", "getAllDesktop");
        Route::get("all-mobile", "getAllMobile");
        Route::post("sort", "sort");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
        Route::delete("{id}", "destroy");
    });

    Route::prefix("cast-category")->controller(\App\Http\Controllers\V1\Admin\CastCategoryController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::get("get", "get");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
    });
    Route::prefix("coupon")->controller(\App\Http\Controllers\V1\Admin\CouponController::class)->group(function () {
        Route::get("dataTable", "dataTable");
        Route::get("generate", "generate");
        Route::post("group", "storeGroup");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
    });

    Route::prefix("campaign-banner")->controller(\App\Http\Controllers\V1\Admin\CampaignBannerController::class)->group(function () {
        Route::get("dataTable/{id}", "dataTable");
        Route::get("list/{type}", "list");
        Route::post("sort", "sort");
        Route::post("/", "store");
        Route::get("{id}", "show");
        Route::put("{id}", "update");
        Route::delete("{id}", "destroy");
    });

});
