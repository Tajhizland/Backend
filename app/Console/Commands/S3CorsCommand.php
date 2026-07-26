<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * بدون CORS درست، آپلود مستقیم از مرورگر کار نمی‌کند.
 * نکته‌ی حیاتی: ETag باید expose شود وگرنه مرحله‌ی complete در multipart
 * شکست می‌خورد چون مرورگر نمی‌تواند ETag پارت‌ها را بخواند.
 */
class S3CorsCommand extends Command
{
    protected $signature = 's3:cors {--show : فقط تنظیمات فعلی را نمایش بده}';

    protected $description = 'اعمال تنظیمات CORS روی باکت برای آپلود مستقیم';

    public function handle(): int
    {
        $client = Storage::disk('s3')->getClient();
        $bucket = config('filesystems.disks.s3.bucket');

        if ($this->option('show')) {
            try {
                $result = $client->getBucketCors(['Bucket' => $bucket]);
                $this->line(json_encode($result['CORSRules'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            } catch (\Throwable $e) {
                $this->warn("CORS خوانده نشد: {$e->getMessage()}");
            }
            return self::SUCCESS;
        }

        $origins = config('upload.cors_origins');

        if (empty($origins)) {
            $this->error('UPLOAD_CORS_ORIGINS در .env تنظیم نشده است. مثال:');
            $this->line('UPLOAD_CORS_ORIGINS=https://panel.example.com,http://localhost:3000');
            return self::FAILURE;
        }

        $client->putBucketCors([
            'Bucket' => $bucket,
            'CORSConfiguration' => [
                'CORSRules' => [[
                    'AllowedOrigins' => $origins,
                    'AllowedMethods' => ['PUT', 'POST', 'GET', 'HEAD', 'DELETE'],
                    'AllowedHeaders' => ['*'],
                    'ExposeHeaders' => ['ETag', 'x-amz-request-id'],
                    'MaxAgeSeconds' => 3600,
                ]],
            ],
        ]);

        $this->info('CORS روی باکت ' . $bucket . ' اعمال شد برای: ' . implode(', ', $origins));

        return self::SUCCESS;
    }
}
