<?php

namespace App\Services\DirectUpload;

use App\Enums\DirectUploadStatus;
use App\Exceptions\BreakException;
use App\Models\DirectUpload;
use App\Repositories\DirectUpload\DirectUploadRepositoryInterface;
use App\Services\S3\S3ServiceInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * لایه‌ی سیاست‌گذاری آپلود مستقیم.
 *
 * قرارداد امنیتی: کلاینت هرگز مسیر فایل، پوشه‌ی مقصد یا سقف حجم را تعیین
 * نمی‌کند؛ فقط نام پروفایل را می‌فرستد. مسیر همیشه اینجا ساخته می‌شود.
 */
class DirectUploadService implements DirectUploadServiceInterface
{
    /** سقف تعداد پارت در استاندارد S3 */
    private const MAX_PARTS = 10000;

    /** حداقل حجم پارت میانی در استاندارد S3 */
    private const MIN_PART_SIZE = 5 * 1024 * 1024;

    public function __construct(
        private DirectUploadRepositoryInterface $directUploadRepository,
        private S3ServiceInterface              $s3Service,
    )
    {
    }

    public function initiate(string $profile, string $fileName, int $size, string $mime, $userId): array
    {
        $config = $this->profile($profile);

        $extension = strtolower((string)pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($extension, $config['extensions'], true))
            $this->fail("فرمت فایل مجاز نیست. فرمت‌های مجاز: " . implode(', ', $config['extensions']));

        if ($mime !== '' && !in_array($mime, $config['mimes'], true))
            $this->fail("نوع فایل ارسالی مجاز نیست.");

        if ($size <= 0)
            $this->fail("حجم فایل نامعتبر است.");

        if ($size > $config['max_size'])
            $this->fail("حجم فایل بیش از حد مجاز است. حداکثر: " . $this->humanSize($config['max_size']));

        $key = trim(config('upload.tmp_prefix'), '/') . '/' . Str::uuid() . '.' . $extension;
        $partSize = $this->resolvePartSize($size);
        $multipart = $size > (int)config('upload.single_put_threshold');
        $ttl = (int)config('upload.url_ttl');

        $uploadId = $multipart
            ? $this->s3Service->createMultipartUpload($key, $mime ?: 'application/octet-stream')
            : null;

        $this->directUploadRepository->create([
            'user_id' => $userId,
            'profile' => $profile,
            'key' => $key,
            'upload_id' => $uploadId,
            'original_name' => mb_substr($fileName, 0, 255),
            'mime' => $mime,
            'size' => $size,
            'status' => DirectUploadStatus::Pending->value,
        ]);

        if (!$multipart) {
            return [
                'key' => $key,
                'multipart' => false,
                'uploadId' => null,
                'partSize' => $size,
                'partCount' => 1,
                'expiresIn' => $ttl,
                'urls' => [
                    ['partNumber' => 1, 'url' => $this->s3Service->signPutObject($key, $mime ?: 'application/octet-stream', $ttl)],
                ],
            ];
        }

        $partCount = (int)ceil($size / $partSize);
        $batch = min($partCount, (int)config('upload.sign_batch_size'));

        return [
            'key' => $key,
            'multipart' => true,
            'uploadId' => $uploadId,
            'partSize' => $partSize,
            'partCount' => $partCount,
            'expiresIn' => $ttl,
            'urls' => $this->sign($key, $uploadId, range(1, $batch), $ttl),
        ];
    }

    public function signParts(string $key, array $partNumbers, $userId): array
    {
        $upload = $this->owned($key, $userId);

        if ($upload->status !== DirectUploadStatus::Pending)
            $this->fail("این آپلود دیگر در حال انجام نیست.");

        if (!$upload->isMultipart())
            $this->fail("این آپلود چندتکه‌ای نیست.");

        $partNumbers = array_values(array_unique(array_map('intval', $partNumbers)));

        foreach ($partNumbers as $partNumber) {
            if ($partNumber < 1 || $partNumber > self::MAX_PARTS)
                $this->fail("شماره‌ی پارت نامعتبر است.");
        }

        return $this->sign($key, $upload->upload_id, $partNumbers, (int)config('upload.url_ttl'));
    }

    public function complete(string $key, array $parts, $userId): array
    {
        $upload = $this->owned($key, $userId);

        if ($upload->status === DirectUploadStatus::Completed)
            return ['key' => $key, 'size' => (int)$upload->confirmed_size];

        if ($upload->status !== DirectUploadStatus::Pending)
            $this->fail("این آپلود دیگر قابل تکمیل نیست.");

        if ($upload->isMultipart()) {
            $normalized = $this->normalizeParts($parts);

            if (empty($normalized))
                $this->fail("اطلاعات پارت‌ها ارسال نشده است.");

            $this->s3Service->completeMultipartUpload($key, $upload->upload_id, $normalized);
        }

        // تنها اعتبارسنجی قابل‌اعتماد: خودِ آبجکت روی S3، نه چیزی که کلاینت ادعا کرده
        $meta = $this->s3Service->head($key);

        if ($meta === null)
            $this->fail("فایل روی فضای ذخیره‌سازی پیدا نشد.");

        $config = $this->profile($upload->profile);

        if ($meta['size'] <= 0 || $meta['size'] > $config['max_size']) {
            $this->s3Service->remove($key);
            $this->directUploadRepository->update($upload, ['status' => DirectUploadStatus::Aborted->value]);
            $this->fail("حجم فایل آپلودشده مجاز نیست.");
        }

        $this->directUploadRepository->update($upload, [
            'status' => DirectUploadStatus::Completed->value,
            'confirmed_size' => $meta['size'],
        ]);

        return ['key' => $key, 'size' => $meta['size']];
    }

    public function abort(string $key, $userId): void
    {
        $upload = $this->owned($key, $userId);

        $this->release($upload);

        $this->directUploadRepository->update($upload, ['status' => DirectUploadStatus::Aborted->value]);
    }

    public function consume(string $key, $userId): string
    {
        $upload = $this->owned($key, $userId);

        if ($upload->status !== DirectUploadStatus::Completed)
            $this->fail("فایل هنوز به‌طور کامل آپلود نشده است.");

        $config = $this->profile($upload->profile);

        $fileName = basename($upload->key);
        $destination = trim($config['folder'], '/') . '/' . $fileName;

        // کپی سمت S3 انجام می‌شود؛ هیچ بایتی از سرور ما عبور نمی‌کند
        $this->s3Service->copy($upload->key, $destination);
        $this->s3Service->remove($upload->key);

        $this->directUploadRepository->update($upload, ['status' => DirectUploadStatus::Consumed->value]);

        return $fileName;
    }

    public function prune(int $hours): int
    {
        $count = 0;

        foreach ($this->directUploadRepository->stale($hours) as $upload) {
            try {
                $this->release($upload);
                $this->directUploadRepository->update($upload, ['status' => DirectUploadStatus::Aborted->value]);
                $count++;
            } catch (\Throwable $e) {
                Log::warning("upload:prune failed for {$upload->key}: {$e->getMessage()}");
            }
        }

        return $count;
    }

    /* ------------------------------------------------------------------ */

    /** آزادسازی آبجکت و پارت‌های نیمه‌کاره‌ای که هنوز فضا اشغال می‌کنند */
    private function release(DirectUpload $upload): void
    {
        if ($upload->isMultipart()) {
            try {
                $this->s3Service->abortMultipartUpload($upload->key, $upload->upload_id);
            } catch (\Throwable $e) {
                // ممکن است قبلاً complete شده باشد؛ در این حالت خودِ آبجکت پاک می‌شود
            }
        }

        try {
            $this->s3Service->remove($upload->key);
        } catch (\Throwable $e) {
            Log::warning("S3 remove failed for {$upload->key}: {$e->getMessage()}");
        }
    }

    private function sign(string $key, string $uploadId, array $partNumbers, int $ttl): array
    {
        $urls = [];

        foreach ($partNumbers as $partNumber) {
            $urls[] = [
                'partNumber' => (int)$partNumber,
                'url' => $this->s3Service->signUploadPart($key, $uploadId, (int)$partNumber, $ttl),
            ];
        }

        return $urls;
    }

    private function normalizeParts(array $parts): array
    {
        $normalized = [];

        foreach ($parts as $part) {
            $number = (int)($part['partNumber'] ?? $part['PartNumber'] ?? 0);
            $etag = (string)($part['etag'] ?? $part['ETag'] ?? '');

            if ($number < 1 || $etag === '') continue;

            $normalized[] = [
                'PartNumber' => $number,
                'ETag' => $etag,
            ];
        }

        usort($normalized, fn($a, $b) => $a['PartNumber'] <=> $b['PartNumber']);

        return $normalized;
    }

    private function owned(string $key, $userId): DirectUpload
    {
        $upload = $this->directUploadRepository->findOwned($key, $userId);

        if (!$upload)
            $this->fail("آپلود موردنظر پیدا نشد.");

        return $upload;
    }

    private function profile(string $profile): array
    {
        $config = config("upload.profiles.{$profile}");

        if (!is_array($config))
            $this->fail("پروفایل آپلود نامعتبر است.");

        return $config;
    }

    /**
     * حجم پارت را طوری بزرگ می‌کند که تعداد پارت‌ها از سقف S3 عبور نکند
     * (مثلاً فایل ۲ گیگی با پارت ۱۰ مگی مشکلی ندارد، ولی این محاسبه
     * سقف را برای فایل‌های خیلی بزرگ‌تر هم امن نگه می‌دارد).
     */
    private function resolvePartSize(int $size): int
    {
        $partSize = max((int)config('upload.part_size'), self::MIN_PART_SIZE);

        while ((int)ceil($size / $partSize) > self::MAX_PARTS) {
            $partSize *= 2;
        }

        return $partSize;
    }

    private function humanSize(int $bytes): string
    {
        if ($bytes >= 1024 ** 3) return round($bytes / (1024 ** 3), 1) . ' گیگابایت';
        if ($bytes >= 1024 ** 2) return round($bytes / (1024 ** 2), 1) . ' مگابایت';
        return round($bytes / 1024, 1) . ' کیلوبایت';
    }

    private function fail(string $message): never
    {
        throw new BreakException($message);
    }
}
