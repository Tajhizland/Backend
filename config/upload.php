<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Direct Upload (Presigned / Multipart)
    |--------------------------------------------------------------------------
    |
    | تنظیمات آپلود مستقیم مرورگر به S3. فایل هرگز از PHP رد نمی‌شود؛ سرور فقط
    | URL امضاشده صادر می‌کند و در پایان صحت آبجکت را بررسی می‌کند.
    |
    */

    /** حجم هر پارت در آپلود چندتکه‌ای (حداقل مجاز S3 برای پارت‌های میانی ۵ مگابایت است) */
    'part_size' => (int)env('UPLOAD_PART_SIZE', 10 * 1024 * 1024),

    /** فایل کوچک‌تر از این حجم با یک PUT ساده آپلود می‌شود (بدون multipart) */
    'single_put_threshold' => (int)env('UPLOAD_SINGLE_PUT_THRESHOLD', 20 * 1024 * 1024),

    /** طول عمر URL های امضاشده به ثانیه */
    'url_ttl' => (int)env('UPLOAD_URL_TTL', 3600),

    /** حداکثر تعداد پارتی که در هر درخواست امضا می‌شود (برای کوتاه نگه‌داشتن TTL) */
    'sign_batch_size' => (int)env('UPLOAD_SIGN_BATCH_SIZE', 50),

    /** پریفیکس موقت؛ فایل ابتدا اینجا می‌نشیند و بعد از تأیید به مسیر نهایی منتقل می‌شود */
    'tmp_prefix' => env('UPLOAD_TMP_PREFIX', 'tmp'),

    /** آپلودهای ناتمام بعد از این تعداد ساعت توسط دستور upload:prune پاک می‌شوند */
    'prune_after_hours' => (int)env('UPLOAD_PRUNE_AFTER_HOURS', 24),

    /*
    |--------------------------------------------------------------------------
    | Profiles
    |--------------------------------------------------------------------------
    |
    | هر پروفایل یک نوع محتوا را توصیف می‌کند. کلاینت فقط نام پروفایل را
    | می‌فرستد؛ مسیر مقصد، سقف حجم و پسوندهای مجاز همیشه سمت سرور تعیین
    | می‌شوند تا کلاینت نتواند روی مسیر دلخواه بنویسد.
    |
    */
    'profiles' => [

        'vlog_video' => [
            'folder' => 'vlog',
            'max_size' => (int)env('UPLOAD_MAX_VIDEO_SIZE', 2 * 1024 * 1024 * 1024),
            'extensions' => ['mp4', 'mov', 'm4v', 'mkv', 'webm'],
            'mimes' => [
                'video/mp4',
                'video/quicktime',
                'video/x-m4v',
                'video/x-matroska',
                'video/webm',
            ],
        ],

        'product_video' => [
            'folder' => 'product',
            'max_size' => (int)env('UPLOAD_MAX_VIDEO_SIZE', 2 * 1024 * 1024 * 1024),
            'extensions' => ['mp4', 'mov', 'm4v', 'mkv', 'webm'],
            'mimes' => [
                'video/mp4',
                'video/quicktime',
                'video/x-m4v',
                'video/x-matroska',
                'video/webm',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | CORS
    |--------------------------------------------------------------------------
    |
    | مبداهایی که اجازه‌ی آپلود مستقیم به باکت دارند. با دستور
    | `php artisan s3:cors` روی باکت اعمال می‌شود.
    | نکته: هدر ETag حتماً باید expose شود وگرنه multipart در مرحله‌ی
    | complete شکست می‌خورد.
    |
    */
    'cors_origins' => array_values(array_filter(
        explode(',', (string)env('UPLOAD_CORS_ORIGINS', ''))
    )),

];
