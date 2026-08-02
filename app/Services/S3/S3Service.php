<?php

namespace App\Services\S3;

use Aws\S3\S3Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class S3Service implements S3ServiceInterface
{
    public function upload($file, $path, $fileName = ""): string
    {
        if ($fileName == "")
            $fileName = time() . "_" . rand(10000, 99999) . '.' . $file->getClientOriginalExtension();
        $filePath = $path . '/' . $fileName;

        // استریم به‌جای file_get_contents تا فایل بزرگ کل حافظه را نگیرد
        $stream = fopen($file->getPathname(), 'rb');

        if ($stream === false)
            throw new \RuntimeException("Unable to read uploaded file: " . $file->getPathname());

        try {
            // Flysystem وقتی محتوا استریم است Content-Type را فقط از روی پسوند
            // حدس می‌زند، نه از محتوا. برای اینکه رفتار دقیقاً مثل حالت قبلی
            // (file_get_contents) بماند، نوع فایل را صریح ست می‌کنیم.
            $options = [];
            $mime = method_exists($file, 'getMimeType') ? $file->getMimeType() : null;
            if ($mime) $options['ContentType'] = $mime;

            Storage::disk('s3')->put($filePath, $stream, $options);
        } finally {
            if (is_resource($stream)) fclose($stream);
        }

        return $fileName;
    }

    public function upload2($file, $path, $fileName = ""): string
    {
        if ($fileName == "")
            $fileName = time() . "_" . rand(10000, 99999) . '.jpg';
        $filePath = $path . '/' . $fileName;
        Storage::disk('s3')->put($filePath, $file);
        return $fileName;
    }

    public function remove($path): void
    {
        Storage::disk('s3')->delete($path);
    }

    public function removeFolder($folderPath): void
    {
        $files = Storage::disk('s3')->allFiles($folderPath);
        Storage::disk('s3')->delete($files);
    }

    public function download(string $s3Path, string $localPath): void
    {
        // خواندن استریمی تا ویدیوی چندصد مگابایتی حافظه‌ی worker را پر نکند
        $read = Storage::disk('s3')->readStream($s3Path);
        if ($read === null || $read === false)
            throw new \RuntimeException("S3 object not found: {$s3Path}");

        $write = fopen($localPath, 'wb');
        try {
            stream_copy_to_stream($read, $write);
        } finally {
            if (is_resource($read)) fclose($read);
            if (is_resource($write)) fclose($write);
        }
    }

    /* ---------------------------------------------------------------------
     | Direct upload (presigned / multipart)
     | ------------------------------------------------------------------ */

    public function signPutObject(string $key, string $contentType, int $ttl): string
    {
        $command = $this->client()->getCommand('PutObject', [
            'Bucket' => $this->bucket(),
            'Key' => $key,
            'ContentType' => $contentType,
        ]);

        return (string)$this->client()
            ->createPresignedRequest($command, "+{$ttl} seconds")
            ->getUri();
    }

    public function createMultipartUpload(string $key, string $contentType): string
    {
        $result = $this->client()->createMultipartUpload([
            'Bucket' => $this->bucket(),
            'Key' => $key,
            'ContentType' => $contentType,
        ]);

        return (string)$result['UploadId'];
    }

    public function signUploadPart(string $key, string $uploadId, int $partNumber, int $ttl): string
    {
        // ContentType عمداً امضا نمی‌شود: مرورگر برای Blob هدر خودش را می‌فرستد و
        // هر هدرِ امضاشده‌ای که دقیقاً مطابق نباشد باعث خطای 403 می‌شود.
        $command = $this->client()->getCommand('UploadPart', [
            'Bucket' => $this->bucket(),
            'Key' => $key,
            'UploadId' => $uploadId,
            'PartNumber' => $partNumber,
        ]);

        return (string)$this->client()
            ->createPresignedRequest($command, "+{$ttl} seconds")
            ->getUri();
    }

    public function completeMultipartUpload(string $key, string $uploadId, array $parts): void
    {
        $this->client()->completeMultipartUpload([
            'Bucket' => $this->bucket(),
            'Key' => $key,
            'UploadId' => $uploadId,
            'MultipartUpload' => ['Parts' => $parts],
        ]);
    }

    public function abortMultipartUpload(string $key, string $uploadId): void
    {
        $this->client()->abortMultipartUpload([
            'Bucket' => $this->bucket(),
            'Key' => $key,
            'UploadId' => $uploadId,
        ]);
    }

    public function head(string $key): ?array
    {
        try {
            $result = $this->client()->headObject([
                'Bucket' => $this->bucket(),
                'Key' => $key,
            ]);
        } catch (\Throwable $e) {
            // «پیدا نشد» و «دسترسی اشتباه» هر دو اینجا می‌افتند؛ بدون لاگ
            // عیب‌یابی تنظیمات S3 تقریباً غیرممکن می‌شود
            Log::debug("S3 headObject failed for {$key}: {$e->getMessage()}");
            return null;
        }

        return [
            'size' => (int)$result['ContentLength'],
            'mime' => (string)($result['ContentType'] ?? ''),
        ];
    }

    public function exists(string $key): bool
    {
        return $this->head($key) !== null;
    }

    public function copy(string $sourceKey, string $destinationKey): void
    {
        $source = $this->bucket() . '/' . ltrim($sourceKey, '/');

        $this->client()->copyObject([
            'Bucket' => $this->bucket(),
            'Key' => $destinationKey,
            'CopySource' => str_replace('%2F', '/', rawurlencode($source)),
        ]);
    }

    public function uploadStream(string $localPath, string $key, ?string $contentType = null): void
    {
        $stream = fopen($localPath, 'rb');
        try {
            Storage::disk('s3')->put(
                $key,
                $stream,
                $contentType ? ['ContentType' => $contentType] : []
            );
        } finally {
            if (is_resource($stream)) fclose($stream);
        }
    }

    private function client(): S3Client
    {
        return Storage::disk('s3')->getClient();
    }

    private function bucket(): string
    {
        return (string)config('filesystems.disks.s3.bucket');
    }
}
