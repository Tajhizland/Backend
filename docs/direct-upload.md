# آپلود مستقیم ویدیو به S3

فایل از مرورگر مستقیماً به فضای ابری می‌رود. سرور اپلیکیشن فقط URL امضاشده صادر
می‌کند و در پایان صحت آبجکت را تأیید می‌کند — هیچ بایتی از nginx و PHP عبور
نمی‌کند، پس `post_max_size`، `client_max_body_size` و تایم‌اوت‌ها بی‌اثر می‌شوند.

## راه‌اندازی

### ۱. متغیرهای محیطی

```dotenv
# باید از قبل تنظیم باشد
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=
AWS_BUCKET=
AWS_ENDPOINT=
AWS_USE_PATH_STYLE_ENDPOINT=true      # برای اکثر سرویس‌های S3-compatible

# صف باید غیر از sync باشد وگرنه ffmpeg داخل ریکوئست اجرا می‌شود
QUEUE_CONNECTION=database

# دامنه‌هایی که اجازه‌ی آپلود مستقیم دارند (با کاما جدا شود)
UPLOAD_CORS_ORIGINS=https://panel.example.com,http://localhost:3000

# اختیاری
UPLOAD_PART_SIZE=10485760             # ۱۰ مگابایت
UPLOAD_URL_TTL=3600
UPLOAD_MAX_VIDEO_SIZE=2147483648      # ۲ گیگابایت
```

### ۲. مایگریشن

```bash
php artisan migrate
```

جدول `direct_uploads` و ستون‌های `video_status` / `video_error` روی `vlogs`
ساخته می‌شوند.

### ۳. CORS باکت

**بدون این مرحله آپلود مستقیم کار نمی‌کند.**

```bash
php artisan s3:cors          # اعمال
php artisan s3:cors --show   # بررسی تنظیمات فعلی
```

نکته‌ی حیاتی: هدر `ETag` باید در `ExposeHeaders` باشد، وگرنه مرورگر نمی‌تواند
ETag پارت‌ها را بخواند و مرحله‌ی complete در multipart شکست می‌خورد. اگر
پرووایدر شما `putBucketCors` را پشتیبانی نمی‌کند، همین قوانین را از پنل خودش
دستی ست کنید.

### ۴. Worker

ترنسکد باید روی یک worker جدا اجرا شود، نه روی ماشین وب:

```bash
php artisan queue:work --timeout=7200 --tries=1
```

نمونه‌ی supervisor:

```ini
[program:taj-transcode]
command=php /var/www/taj/artisan queue:work --timeout=7200 --tries=1
autostart=true
autorestart=true
stopwaitsecs=7300
numprocs=1
```

`ffmpeg` و `ffprobe` باید روی همان ماشین نصب باشند.

### ۵. پاک‌سازی آپلودهای رهاشده

اگر کاربر آپلود را نیمه‌کاره رها کند، پارت‌ها روی باکت فضا اشغال می‌کنند.
این را در `routes/console.php` زمان‌بندی کنید:

```php
Schedule::command('upload:prune')->daily();
```

یا دستی: `php artisan upload:prune --hours=24`

## جریان کار

```
مرورگر                          Laravel                         S3
  │                                │                             │
  ├─ POST admin/upload/initiate ──▶│                             │
  │                                ├─ CreateMultipartUpload ────▶│
  │◀── key + uploadId + URL ها ────┤                             │
  │                                │                             │
  ├─ PUT پارت ۱..N (موازی) ────────────────────────────────────▶│
  │◀── ETag هر پارت ───────────────────────────────────────────┤
  │                                │                             │
  ├─ POST admin/upload/complete ──▶│                             │
  │                                ├─ CompleteMultipartUpload ──▶│
  │                                ├─ HeadObject (اعتبارسنجی) ──▶│
  │◀── تأیید ──────────────────────┤                             │
  │                                │                             │
  ├─ POST admin/vlog/store-direct ▶│  (فقط videoKey + پوستر)     │
  │                                ├─ CopyObject tmp/ → vlog/ ──▶│
  │                                ├─ dispatch ConvertVideoToHls │
  │◀── vlog با video_status=queued ┤                             │
  │                                │                             │
  ├─ GET admin/vlog/video-status ─▶│  (هر ۵ ثانیه تا ready)      │
```

## اندپوینت‌ها

همه زیر `api/v1/admin` و پشت `auth:sanctum` + `AdminMiddleware`:

| متد | مسیر | کار |
|---|---|---|
| POST | `upload/initiate` | اعتبارسنجی، ساخت کلید، صدور اولین دسته URL |
| POST | `upload/sign-parts` | امضای دسته‌ی بعدی پارت‌ها |
| POST | `upload/complete` | تکمیل multipart + تأیید آبجکت |
| POST | `upload/abort` | لغو و آزادسازی پارت‌ها |
| POST | `vlog/store-direct` | ثبت ولاگ با `videoKey` |
| GET | `vlog/video-status/{id}` | وضعیت ترنسکد |

## تصمیم‌های امنیتی

- **مسیر فایل را همیشه سرور می‌سازد.** کلاینت فقط نام پروفایل
  (`vlog_video`) را می‌فرستد؛ پوشه‌ی مقصد، سقف حجم و پسوندهای مجاز در
  `config/upload.php` تعریف شده‌اند.
- **هر آپلود در جدول `direct_uploads` ثبت و به کاربر گره می‌خورد.** کاربر
  نمی‌تواند کلید آپلود کاربر دیگری را در فرم استفاده کند.
- **با presigned PUT نمی‌توان حجم را محدود کرد**، پس بعد از تکمیل با
  `HeadObject` اندازه‌ی واقعی بررسی و در صورت تخطی آبجکت حذف می‌شود.
- **اعتبارسنجی نوع فایل به کلاینت اعتماد نمی‌کند**؛ داور نهایی `ffprobe` در
  جاب است که اگر فایل ویدیو نباشد شکست می‌خورد و رکورد `failed` می‌شود.
- URL های امضاشده مجوز حامل‌اند و لحظه‌ای و دسته‌ای صادر می‌شوند تا عمرشان
  کوتاه بماند.

## عیب‌یابی

| نشانه | علت محتمل |
|---|---|
| خطای CORS روی PUT | `php artisan s3:cors` اجرا نشده یا دامنه در `UPLOAD_CORS_ORIGINS` نیست |
| «هدر ETag خوانده نشد» | `ETag` در `ExposeHeaders` باکت نیست |
| ۴۰۳ روی پارت‌ها | ساعت سرور با واقعیت اختلاف دارد، یا URL منقضی شده (TTL را زیاد کنید) |
| ۴۰۰ با «Only one auth mechanism» | هدر `Authorization` به درخواست S3 اضافه شده — نباید از axios پروژه استفاده شود |
| `video_status` روی `queued` می‌ماند | worker بالا نیست |
| `video_status` می‌شود `failed` | متن خطا در ستون `video_error` و در لاگ لاراول |
