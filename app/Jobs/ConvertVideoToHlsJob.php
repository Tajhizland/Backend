<?php

namespace App\Jobs;

use App\Models\Vlog;
use App\Services\S3\S3ServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Support\Str;

class ConvertVideoToHlsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Vlog $vlog;
    private S3ServiceInterface $s3Service;

    public function __construct(Vlog $vlog, S3ServiceInterface $s3Service)
    {
        $this->vlog = $vlog;
        $this->s3Service = $s3Service;
    }

    public function handle(): void
    {
        $this->vlog->update(['status' => 'processing']);

        // شناسه و مسیرهای موقت
        $videoId = Str::uuid()->toString();
        $tempPath = storage_path("app/temp_videos/{$videoId}.mp4");
        $outputDir = storage_path("app/hls_temp/{$videoId}");

        if (!file_exists(dirname($tempPath))) mkdir(dirname($tempPath), 0777, true);
        if (!file_exists($outputDir)) mkdir($outputDir, 0777, true);

        // دانلود فایل mp4 از S3
        $this->s3Service->download($this->vlog->video, $tempPath);

        // اجرای ffmpeg برای تولید HLS (همه کیفیت‌ها یکجا)
        $ffmpeg = <<<EOL
ffmpeg -i "{$tempPath}" \
-filter:v:0 scale=w=426:h=240 -c:v:0 libx264 -b:v:0 400k -c:a aac -ar 48000 -b:a 128k \
-filter:v:1 scale=w=640:h=360 -c:v:1 libx264 -b:v:1 800k \
-filter:v:2 scale=w=854:h=480 -c:v:2 libx264 -b:v:2 1000k \
-filter:v:3 scale=w=1280:h=720 -c:v:3 libx264 -b:v:3 1400k \
-map 0:a -map 0:v -map 0:a -map 0:v -map 0:a -map 0:v -map 0:a -map 0:v \
-var_stream_map "v:0,a:0 v:1,a:1 v:2,a:2 v:3,a:3" \
-f hls -hls_time 6 -hls_playlist_type vod \
-master_pl_name master.m3u8 \
-hls_segment_filename "{$outputDir}/%v/segment_%03d.ts" "{$outputDir}/%v/index.m3u8"
EOL;

        exec($ffmpeg, $output, $returnCode);
        if ($returnCode !== 0) {
            $this->vlog->update(['status' => 'failed']);
            throw new \RuntimeException("FFmpeg failed: " . implode("\n", $output));
        }

        // حذف فایل mp4 موقت
        unlink($tempPath);

        // آپلود همه فایل‌های HLS به S3
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($outputDir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $fileInfo) {
            if (!$fileInfo->isFile()) continue;

            $localFile = $fileInfo->getPathname();
            $relativePath = str_replace($outputDir . '/', '', $localFile);
            $filePath = "hls/{$videoId}/" . dirname($relativePath);
            $filename = basename($relativePath);

            $this->s3Service->upload(new File($localFile), $filePath, $filename);
        }

        // پاک کردن فولدر موقت HLS
        $this->deleteDirectory($outputDir);

        // به‌روز رسانی رکورد ویدیو
        $this->vlog->update([
            'hls' => "{$videoId}/master.m3u8",
        ]);
    }

    private function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) return;

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = "$dir/$file";
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }

        rmdir($dir);
    }
}
