<?php

namespace App\Services\DirectUpload;

interface DirectUploadServiceInterface
{
    /** اعتبارسنجی، ساخت کلید موقت و صدور اولین دسته URL امضاشده */
    public function initiate(string $profile, string $fileName, int $size, string $mime, $userId): array;

    /** امضای دسته‌ی بعدی پارت‌ها */
    public function signParts(string $key, array $partNumbers, $userId): array;

    /** تکمیل multipart و تأیید آبجکت روی S3 */
    public function complete(string $key, array $parts, $userId): array;

    /** لغو آپلود و آزادسازی پارت‌های نیمه‌کاره */
    public function abort(string $key, $userId): void;

    /**
     * انتقال فایل از مسیر موقت به مسیر نهایی پروفایل و علامت‌زدن رکورد به‌عنوان
     * مصرف‌شده. نام فایل نهایی (بدون پوشه) برمی‌گرداند.
     */
    public function consume(string $key, $userId): string;

    /** پاک‌سازی آپلودهای رهاشده */
    public function prune(int $hours): int;
}
