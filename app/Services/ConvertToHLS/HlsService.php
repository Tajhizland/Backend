<?php

namespace App\Services\ConvertToHLS;

use App\Services\S3\S3ServiceInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class HlsService implements HlsServiceInterface
{
    public function __construct
    (
        private S3ServiceInterface $s3Service
    )
    {
    }

    public function convertAndUploadS3(UploadedFile $file)
    {
        $outputDir = $this->convertToHls($file);
        $this->generateMasterPlaylist($outputDir);

        $videoId = basename($outputDir);

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($outputDir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $fileInfo) {
            /** @var \SplFileInfo $fileInfo */
            if (!$fileInfo->isFile()) continue;

            $localFile = $fileInfo->getPathname();

            // ساخت مسیر مناسب برای ذخیره در S3 (حفظ ساختار فولدرها)
            $relativePath = str_replace($outputDir . '/', '', $localFile);
            $filePath = "hls/{$videoId}/{$relativePath}";
            $filename = basename($filePath);
            $filePath = str_replace("/" . basename($filePath), "", $filePath);
            // آپلود فایل به S3
            $this->s3Service->upload(new \Illuminate\Http\File($localFile), $filePath, $filename);
        }
        $this->deleteDirectory($outputDir);
        return "{$videoId}/master.m3u8";
    }

    private function convertToHls(UploadedFile $file): string
    {
        $videoId = \Str::uuid()->toString();
        $tempPath = storage_path("app/temp_videos/{$videoId}.mp4");

        // ذخیره فایل موقت
        if (!file_exists(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0777, true);
        }
        $file->move(dirname($tempPath), basename($tempPath));

        // دایرکتوری خروجی HLS
        $outputDir = storage_path("app/hls_temp/{$videoId}");
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        // ایجاد پوشه‌های کیفیت‌های مختلف
        $qualityDirs = ['240p', '360p', '480p', '720p'];
        foreach ($qualityDirs as $qualityDir) {
            $dirPath = "{$outputDir}/{$qualityDir}";
            if (!file_exists($dirPath)) {
                mkdir($dirPath, 0777, true);
            }
        }

        $masterPlaylistPath = "{$outputDir}/master.m3u8";

        // اجرای ffmpeg برای ایجاد کیفیت‌های مختلف
//        $ffmpeg = <<<EOL
//ffmpeg -i "{$tempPath}" -preset veryfast -g 48 -sc_threshold 0 \
//-map 0:v:0 -map 0:a:0 -c:v:0 libx264 -b:v:0 400k -c:a:0 aac -b:a:0 128k \
//-f hls -hls_time 6 -hls_playlist_type vod \
//-hls_segment_filename "{$outputDir}/240p/segment_%03d.ts" "{$outputDir}/240p/240p.m3u8"
//
//ffmpeg -i "{$tempPath}" -preset veryfast -g 48 -sc_threshold 0 \
//-map 0:v:0 -map 0:a:0 -c:v:0 libx264 -b:v:0 800k -c:a:0 aac -b:a:0 128k \
//-f hls -hls_time 6 -hls_playlist_type vod \
//-hls_segment_filename "{$outputDir}/360p/segment_%03d.ts" "{$outputDir}/360p/360p.m3u8"
//
//ffmpeg -i "{$tempPath}" -preset veryfast -g 48 -sc_threshold 0 \
//-map 0:v:0 -map 0:a:0 -c:v:0 libx264 -b:v:0 1000k -c:a:0 aac -b:a:0 128k \
//-f hls -hls_time 6 -hls_playlist_type vod \
//-hls_segment_filename "{$outputDir}/480p/segment_%03d.ts" "{$outputDir}/480p/480p.m3u8"
//
//ffmpeg -i "{$tempPath}" -preset veryfast -g 48 -sc_threshold 0 \
//-map 0:v:0 -map 0:a:0 -c:v:0 libx264 -b:v:0 1400k -c:a:0 aac -b:a:0 128k \
//-f hls -hls_time 6 -hls_playlist_type vod \
//-hls_segment_filename "{$outputDir}/720p/segment_%03d.ts" "{$outputDir}/720p/720p.m3u8"
//EOL;
//
//        // اجرای دستورات ffmpeg
//        exec($ffmpeg);

        $commands = [
            'ffmpeg -i "'.$tempPath.'" -preset veryfast -g 48 -sc_threshold 0 -map 0:v:0 -map 0:a:0 -c:v:0 libx264 -b:v:0 400k -c:a:0 aac -b:a:0 128k -f hls -hls_time 6 -hls_playlist_type vod -hls_segment_filename "'.$outputDir.'/240p/segment_%03d.ts" "'.$outputDir.'/240p/240p.m3u8"',

            'ffmpeg -i "'.$tempPath.'" -preset veryfast -g 48 -sc_threshold 0 -map 0:v:0 -map 0:a:0 -c:v:0 libx264 -b:v:0 800k -c:a:0 aac -b:a:0 128k -f hls -hls_time 6 -hls_playlist_type vod -hls_segment_filename "'.$outputDir.'/360p/segment_%03d.ts" "'.$outputDir.'/360p/360p.m3u8"',

            'ffmpeg -i "'.$tempPath.'" -preset veryfast -g 48 -sc_threshold 0 -map 0:v:0 -map 0:a:0 -c:v:0 libx264 -b:v:0 1000k -c:a:0 aac -b:a:0 128k -f hls -hls_time 6 -hls_playlist_type vod -hls_segment_filename "'.$outputDir.'/480p/segment_%03d.ts" "'.$outputDir.'/480p/480p.m3u8"',

            'ffmpeg -i "'.$tempPath.'" -preset veryfast -g 48 -sc_threshold 0 -map 0:v:0 -map 0:a:0 -c:v:0 libx264 -b:v:0 1400k -c:a:0 aac -b:a:0 128k -f hls -hls_time 6 -hls_playlist_type vod -hls_segment_filename "'.$outputDir.'/720p/segment_%03d.ts" "'.$outputDir.'/720p/720p.m3u8"',
        ];

        foreach ($commands as $cmd) {
            exec($cmd . ' 2>&1', $output, $returnCode);
            if ($returnCode !== 0) {
                \Log::error('FFMPEG error: '.implode("\n", $output));
            }
        }


        // حذف فایل موقت mp4
//        unlink($tempPath);

        // بازگرداندن مسیر فولدر خروجی
        return $outputDir;
    }

    private function generateMasterPlaylist(string $outputDir): void
    {
        $lines = [
            "#EXTM3U",
            "#EXT-X-VERSION:3",
            "",
            "#EXT-X-STREAM-INF:BANDWIDTH=100000,RESOLUTION=426x240",
            "240p/240p.m3u8",
            "#EXT-X-STREAM-INF:BANDWIDTH=300000,RESOLUTION=640x360",
            "360p/360p.m3u8",
            "#EXT-X-STREAM-INF:BANDWIDTH=400000,RESOLUTION=854x480",
            "480p/480p.m3u8",
            "#EXT-X-STREAM-INF:BANDWIDTH=800000,RESOLUTION=1280x720",
            "720p/720p.m3u8",
        ];

        $playlistPath = "{$outputDir}/master.m3u8";
        file_put_contents($playlistPath, implode("\n", $lines));
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


