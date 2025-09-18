<?php

namespace App\Services\ConvertToHLS;

use App\Services\S3\S3ServiceInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Str;

class HlsConvert implements HlsServiceInterface
{
    public function __construct(
        private S3ServiceInterface $s3Service
    ) {}

    public function convertAndUploadS3(UploadedFile $file): string
    {
        // 1) تبدیل به HLS (همهٔ کیفیت‌ها یک‌جا)
        $outputDir = $this->convertToHls($file);

        // 2) آپلود همهٔ فایل‌ها به S3
        $videoId = basename($outputDir);

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($outputDir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $fileInfo) {
            /** @var \SplFileInfo $fileInfo */
            if (!$fileInfo->isFile()) continue;

            $localFile = $fileInfo->getPathname();
            $relativePath = str_replace($outputDir . '/', '', $localFile);

            // مسیر در S3
            $filePath = "hls/{$videoId}/" . dirname($relativePath);
            $filename = basename($relativePath);

            $this->s3Service->upload(new \Illuminate\Http\File($localFile), $filePath, $filename);
        }

        // 3) پاک کردن فولدر موقت
        $this->deleteDirectory($outputDir);

        // 4) آدرس master.m3u8
        return "{$videoId}/master.m3u8";
    }

    private function convertToHls(UploadedFile $file): string
    {
        $videoId = Str::uuid()->toString();
        $tempPath = storage_path("app/temp_videos/{$videoId}.mp4");

        if (!file_exists(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0777, true);
        }
        $file->move(dirname($tempPath), basename($tempPath));

        $outputDir = storage_path("app/hls_temp/{$videoId}");
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        // دستور ffmpeg (یک بار همهٔ کیفیت‌ها را تولید می‌کند)
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

        exec($ffmpeg);

        unlink($tempPath);

        return $outputDir;
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
