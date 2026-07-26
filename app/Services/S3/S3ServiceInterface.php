<?php

namespace App\Services\S3;

interface S3ServiceInterface
{
    public function download(string $s3Path, string $localPath): void;
    public function upload($file, $path , $fileName=""): string;
    public function upload2($file, $path , $fileName=""): string;
    public function remove($path): void;
    public function removeFolder($folderPath): void;

    /* ---------------------------------------------------------------------
     | Direct upload (presigned / multipart)
     | ------------------------------------------------------------------ */

    /** URL امضاشده برای یک PUT ساده (فایل‌های کوچک) */
    public function signPutObject(string $key, string $contentType, int $ttl): string;

    /** شروع آپلود چندتکه‌ای؛ uploadId برمی‌گرداند */
    public function createMultipartUpload(string $key, string $contentType): string;

    /** URL امضاشده برای یک پارت مشخص */
    public function signUploadPart(string $key, string $uploadId, int $partNumber, int $ttl): string;

    /** تکمیل آپلود چندتکه‌ای؛ $parts آرایه‌ای از ["PartNumber" => int, "ETag" => string] */
    public function completeMultipartUpload(string $key, string $uploadId, array $parts): void;

    /** لغو آپلود چندتکه‌ای و آزادسازی پارت‌های آپلودشده */
    public function abortMultipartUpload(string $key, string $uploadId): void;

    /** متادیتای آبجکت (size, mime) یا null اگر وجود نداشته باشد */
    public function head(string $key): ?array;

    public function exists(string $key): bool;

    /** کپی سمت سرور S3 (بدون عبور از پهنای باند ما) */
    public function copy(string $sourceKey, string $destinationKey): void;

    /** آپلود استریمی؛ برخلاف upload() کل فایل را در حافظه نگه نمی‌دارد */
    public function uploadStream(string $localPath, string $key, ?string $contentType = null): void;
}
