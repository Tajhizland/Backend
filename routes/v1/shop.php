<?php

use App\Events\OrderPaidEvent;
use App\Http\Controllers\V1\Shop\CartController;
use App\Http\Controllers\V1\Shop\CategoryController;
use App\Http\Controllers\V1\Shop\FavoriteController;
use App\Http\Controllers\V1\Shop\HomePageController;
use App\Http\Controllers\V1\Shop\NewsController;
use App\Http\Controllers\V1\Shop\ProductController;
use App\Http\Controllers\V1\Shop\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;


Route::get('/homepage', [HomePageController::class, "index"]);
Route::get('/menu', [\App\Http\Controllers\V1\Shop\MenuController::class, "get"]);
Route::get('/d', [\App\Http\Controllers\V1\Admin\DashboardController::class, "index"]);
Route::get('province', [\App\Http\Controllers\V1\Shop\AddressController::class, "getProvinces"]);
Route::get('province/{id}/city', [\App\Http\Controllers\V1\Shop\AddressController::class, "getCities"]);
Route::post('contact', [\App\Http\Controllers\V1\Shop\ContactController::class, "store"]);

Route::post('goftino/sync', [\App\Http\Controllers\V1\Shop\ChatInfoController::class, "sync"])->middleware("auth:sanctum");
Route::get('emalls/list', [\App\Http\Controllers\V1\Shop\EmallsController::class, "list"]);
//Route::get('torob/list', [\App\Http\Controllers\V1\Shop\TorobController::class, "get"]);
Route::post('footprint', [\App\Http\Controllers\V1\Shop\FootprintController::class, "handle"]);
Route::get('checkout/delivery', [\App\Http\Controllers\V1\Shop\CheckoutController::class, "getShippingMethods"])->middleware("auth:sanctum");
Route::post('torob/product', [\App\Http\Controllers\V1\Shop\TorobController::class, "list"]);


Route::middleware("auth:sanctum")->prefix("cart")->controller(CartController::class)->group(function () {
    Route::get('/', "get");
    Route::post('/', "addToCart");
    Route::delete('/', "clearAll");
    Route::post('merge', "merge");
    Route::delete('item', "removeItem");
    Route::patch('increase', "increase");
    Route::patch('decrease', "decrease");
});

Route::middleware("auth:sanctum")->prefix("favorite")->controller(FavoriteController::class)->group(function () {
    Route::get('/', "index");
    Route::post('/', "addProduct");
    Route::delete('/', "removeProduct");
});

Route::get('product/stock', [ProductController::class, "getStockProducts"]);

Route::prefix("search")->controller(SearchController::class)->group(function () {
    Route::post('/', "index");
    Route::post('paginate', "paginate");
});

Route::prefix("product")->controller(ProductController::class)->group(function () {
    Route::post('find', "find")->withoutMiddleware(\App\Http\Middleware\Fa2EnMiddleware::class);
    Route::get('discount', "getDiscountedProducts");
});

Route::prefix("coupon")->group(function () {
    Route::post("check", [\App\Http\Controllers\V1\Shop\CouponController::class, "check"])->middleware("auth:sanctum");
});

Route::prefix("group")->group(function () {
    Route::post('find', [\App\Http\Controllers\V1\Shop\GroupController::class, "find"])->withoutMiddleware(\App\Http\Middleware\Fa2EnMiddleware::class);
});

Route::prefix("category")->controller(CategoryController::class)->group(function () {
    Route::post('find', "index")->withoutMiddleware(\App\Http\Middleware\Fa2EnMiddleware::class);
    Route::post('group', "groupListing")->withoutMiddleware(\App\Http\Middleware\Fa2EnMiddleware::class);
});
Route::prefix("brand")->controller(\App\Http\Controllers\V1\Shop\BrandController::class)->group(function () {
    Route::post('find', "index")->withoutMiddleware(\App\Http\Middleware\Fa2EnMiddleware::class);
    Route::get('/', "list");
});

Route::prefix("news")->controller(NewsController::class)->group(function () {
    Route::post('find', "findByUrl")->withoutMiddleware(\App\Http\Middleware\Fa2EnMiddleware::class);
    Route::get('/', "paginate");
});

Route::prefix("landing")->group(function () {
    Route::post('find', [\App\Http\Controllers\V1\Shop\LandingController::class, "findByUrl"])->withoutMiddleware(\App\Http\Middleware\Fa2EnMiddleware::class);
});

Route::prefix("page")->group(function () {
    Route::post('find', [\App\Http\Controllers\V1\Shop\PageController::class, "findByUrl"])->withoutMiddleware(\App\Http\Middleware\Fa2EnMiddleware::class);
});

Route::get('faq', [\App\Http\Controllers\V1\Shop\FaqController::class, "getActive"]);

Route::middleware("auth:sanctum")->prefix("address")
    ->controller(\App\Http\Controllers\V1\Shop\AddressController::class)->group(function () {
        Route::get('/', "getAll");
        Route::post('/', "updateOrCreate");
        Route::get('active', "findActive");
        Route::patch('active', "changeActive");
    });

Route::prefix("delivery")->controller(\App\Http\Controllers\V1\Shop\DeliveryController::class)->group(function () {
    Route::get('/', "getActives");
    Route::patch('select', "select")->middleware("auth:sanctum");
});

Route::prefix("payment")->group(function () {
    Route::post('/', [\App\Http\Controllers\V1\Shop\PaymentController::class, "requestPayment"])->middleware("auth:sanctum");
    Route::get('verify', [\App\Http\Controllers\V1\Shop\PaymentController::class, "verifyPayment"]);
    Route::post('digipay', [\App\Http\Controllers\V1\Shop\PaymentController::class, "verifyDigipay"]);
    Route::get('digipay', [\App\Http\Controllers\V1\Shop\PaymentController::class, "verifyDigipay"]);
    Route::post('snappay', [\App\Http\Controllers\V1\Shop\PaymentController::class, "verifySnappay"]);
    Route::post('snappay/eligible', [\App\Http\Controllers\V1\Shop\PaymentController::class, "snappPayEligible"]);
    Route::post('wallet', [\App\Http\Controllers\V1\Shop\WalletController::class, "paymentOrderByWallet"])->middleware("auth:sanctum");
});

Route::middleware("auth:sanctum")->prefix("on-hold-order")
    ->controller(\App\Http\Controllers\V1\Shop\OnHoldOrderController::class)->group(function () {
        Route::get('/', "userHoldOnPaginate");
        Route::post('{id}/payment', "payment");
        Route::post('{id}/wallet', "paymentByWallet");
        Route::get('{id}/checkout', "checkout");
        Route::get('{id}/checkout/delivery', "shippingMethods");
        Route::post('{id}/checkout/coupon', "checkCoupon");
        Route::post('{id}/checkout/payment', "checkoutPayment");
    });

Route::post('comment', [\App\Http\Controllers\V1\Shop\CommentController::class, "store"])->middleware("auth:sanctum");

Route::prefix("guaranty")->group(function () {
    Route::post('find', [\App\Http\Controllers\V1\Shop\GuarantyController::class, "findByUrl"])->withoutMiddleware(\App\Http\Middleware\Fa2EnMiddleware::class);
});

Route::prefix("vlog")->controller(\App\Http\Controllers\V1\Shop\VlogController::class)->group(function () {
    Route::post('find', "find")->withoutMiddleware(\App\Http\Middleware\Fa2EnMiddleware::class);
    Route::post('category', "get")->withoutMiddleware(\App\Http\Middleware\Fa2EnMiddleware::class);
    Route::get('/', "listing");
});
Route::get('vlog-category', [\App\Http\Controllers\V1\Admin\VlogCategoryController::class, "list"]);
Route::get('special-product', [\App\Http\Controllers\V1\Shop\SpecialProductController::class, "list"]);
Route::get('sample', [\App\Http\Controllers\V1\Shop\SampleController::class, "index"]);
Route::prefix("sitemap")->controller(\App\Http\Controllers\V1\Shop\SitemapController::class)->group(function () {
    Route::get('product', "getProductSitemap");
    Route::get('category', "getCategorySitemap");
    Route::get('brand', "getBrandSitemap");
    Route::get('vlog', "getVlogSitemap");
    Route::get('blog', "getBlogSitemap");
    Route::get('guaranty', "getGuarantySitemap");
    Route::get('landing', "getLandingSitemap");
});
Route::get('leading', [\App\Http\Controllers\V1\Shop\LeadingController::class, "index"]);
Route::prefix("compare")->controller(\App\Http\Controllers\V1\Shop\CompareController::class)->group(function () {
    Route::post('search', "searchProduct");
    Route::post('product', "getProducts");
    Route::get('{id}', "findProduct");
});

Route::prefix("charge")->controller(\App\Http\Controllers\V1\Shop\WalletController::class)->group(function () {
    Route::post('/', "chargeWallet")->middleware("auth:sanctum");
    Route::get('verify', "verifyWallet");
});

Route::prefix('category-view-history')
    ->controller(\App\Http\Controllers\V1\Shop\CategoryViewHistoryController::class)->group(function () {
        Route::post('/', "store")->middleware("auth:sanctum");
        Route::post('ip', "store");
        Route::get('suggest', "suggest")->middleware("auth:sanctum");
        Route::get('suggest/ip', "suggestIp");
    });

Route::prefix('cast')->controller(\App\Http\Controllers\V1\Shop\CastController::class)->group(function () {
    Route::get('/', "index");
    Route::post('find', "find");
});
Route::get('cast-category', [\App\Http\Controllers\V1\Shop\CastCategoryController::class, "index"]);


Route::middleware("auth:sanctum")->prefix("order")
    ->controller(\App\Http\Controllers\V1\Shop\OrderController::class)->group(function () {
        Route::get("/", "index");
        Route::get("{id}", "show");
    });

Route::get('info', function () {
    phpinfo();
});
Route::get('d', function () {
    $items = \App\Models\PopularProduct::all();
    $arr = [];
    foreach ($items as $item) {
        $colors = $item->product->activeProductColors;
        foreach ($colors as $color) {
            $di = \App\Models\DiscountItem::where("product_color_id", $color->id)->whereHas("discount", function ($query) {
                $query->where("status", 1);
            })->first();

            if ($di) {
                \App\Models\DiscountItem::where("id", $di->id)->update(["top" => 1]);
//                $di->top = 1;
//                $di->save();
                $arr[] = $di;
            }
        }
    }
    dd($arr);
});

Route::post('per', function (Request $request) {

    $routes = $request->input('routes', []);

    foreach ($routes as $route) {
        \App\Models\Permission::firstOrCreate(
            ['value' => $route],
            ['name' => $route] // یا هر ترجمه‌ای که بعداً اضافه می‌کنی
        );
    }

    return response()->json(['success' => true, 'message' => 'Permissions synced']);
});

Route::get('sp', function (Request $request , \App\Services\DigiPay\DigiPayService $service) {
$s=$service->request(1500000000,"09194961416",2,\App\Models\OrderItem::where("order_id",34500)->get());
dd($s);
});

//Route::get('test', function (\App\Services\Sms\SmsServiceInterface $smsService) {
//    $order = \App\Models\Order::find(15);
//    event(new OrderPaidEvent($order));
//});
//Route::get('info2', function () {
//    $news = \App\Models\Product::all();
//    foreach ($news as $new) {
//        $content = $new->review;
//        $content = str_replace("http://tajhizland.com/upload/", "https://c778665.parspack.net/upload/upload/", $content);
//        $new->review = $content;
//        $new->save();
//    }
//});

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
