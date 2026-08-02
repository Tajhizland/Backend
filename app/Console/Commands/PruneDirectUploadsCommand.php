<?php

namespace App\Console\Commands;

use App\Services\DirectUpload\DirectUploadServiceInterface;
use Illuminate\Console\Command;

class PruneDirectUploadsCommand extends Command
{
    protected $signature = 'upload:prune {--hours= : سن آپلودهای رهاشده به ساعت}';

    protected $description = 'حذف آپلودهای مستقیمِ نیمه‌کاره و پارت‌های رهاشده روی S3';

    public function handle(DirectUploadServiceInterface $directUploadService): int
    {
        $hours = (int)($this->option('hours') ?: config('upload.prune_after_hours'));

        $count = $directUploadService->prune($hours);

        $this->info("{$count} آپلود رهاشده پاک شد.");

        return self::SUCCESS;
    }
}
