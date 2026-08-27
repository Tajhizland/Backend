<?php

return [
    "image_base_url" => "https://c778665.parspack.net/c778665/",
    "website_base_url" => "https://tajhizland.com/",
    "paginated_size" => 24,
    "search_item_limit" => 5,
    "home_page_item_limit" => 10,
    "order_payment_expire_day" => 1,
    "order_returned_expire_day" => 7,
    "default_gateway" => 1,
    "order_payment_expire_hour" => 24,
    "register" => [
        "code_expire_minutes" => 2,
    ],
    "reset_password" => [
        "code_expire_minutes" => 2,
    ],

    /*
    |--------------------------------------------------------------------------
    | Home page
    |--------------------------------------------------------------------------
    |
    | تنظیمات اندپوینت GET /api/v1/homepage
    | cache_ttl را روی 0 بگذارید تا کش غیرفعال شود.
    |
    */
    "home_page" => [
        "cache_key" => "shop:home_page:v1",
        "cache_ttl" => (int)env("HOME_PAGE_CACHE_TTL", 300),

        "top_discount_limit" => (int)env("HOME_PAGE_TOP_DISCOUNT_LIMIT", 24),
        "category_product_limit" => 8,
        // تعداد محصول تصادفیِ بخش «منتخب تجهیزلند» (دسته‌بندی‌هایش در پنل تعریف می‌شود)
        "random_product_limit" => (int)env("HOME_PAGE_RANDOM_PRODUCT_LIMIT", 10),
        /*
        | عمر کشِ «فهرست شناسه‌ی محصولات کاندید» برای بخش منتخب.
        | خودِ انتخاب تصادفی کش نمی‌شود و در هر ریکوئست دوباره انجام می‌گیرد؛
        | این عدد فقط تعیین می‌کند چند وقت یک‌بار فهرست کاندیدها از دیتابیس تازه شود.
        */
        "random_product_candidate_ttl" => (int)env("HOME_PAGE_RANDOM_CANDIDATE_TTL", 600),
        "vlog_limit" => 4,
        "news_limit" => 4,
        "brand_limit" => 12,
        "news_excerpt_length" => 300,
    ],

    /*
    | تعداد تصویری که برای کارت محصول (لیست‌ها/اسلایدرها) لود می‌شود.
    */
    "product_card" => [
        "image_limit" => 4,
    ],
];
