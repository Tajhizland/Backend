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

    private array $qualities = [
        '240p' => ['width' => 426, 'height' => 240, 'bitrate' => '400k'],
        '360p' => ['width' => 640, 'height' => 360, 'bitrate' => '800k'],
        '480p' => ['width' => 854, 'height' => 480, 'bitrate' => '1000k'],
        '720p' => ['width' => 1280, 'height' => 720, 'bitrate' => '1400k'],
    ];

    public function __construct(Vlog $vlog, S3ServiceInterface $s3Service)
    {
        $this->vlog = $vlog;
        $this->s3Service = $s3Service;
    }

    public function handle(): void
    {
        $videoId = Str::uuid()->toString();
        $tempPath = storage_path("app/temp_videos/{$videoId}.mp4");
        $outputDir = storage_path("app/hls_temp/{$videoId}");

        if (!file_exists(dirname($tempPath))) mkdir(dirname($tempPath), 0777, true);
        if (!file_exists($outputDir)) mkdir($outputDir, 0777, true);

        // دانلود فایل mp4 از S3
        $this->s3Service->download($this->vlog->video, $tempPath);

        $playlistPaths = [];

        // ایجاد هر کیفیت جداگانه
        foreach ($this->qualities as $label => $q) {
            $dir = "{$outputDir}/{$label}";
            if (!file_exists($dir)) mkdir($dir, 0777, true);

            $outputPlaylist = "{$dir}/index.m3u8";

            $ffmpeg = "ffmpeg -i \"{$tempPath}\" -vf scale={$q['width']}:{$q['height']} " .
                "-c:v libx264 -b:v {$q['bitrate']} -c:a aac -ar 48000 -b:a 128k " .
                "-f hls -hls_time 6 -hls_playlist_type vod " .
                "-hls_segment_filename \"{$dir}/segment_%03d.ts\" \"{$outputPlaylist}\"";

            exec($ffmpeg, $output, $returnCode);

            if ($returnCode !== 0) {
                throw new \RuntimeException("FFmpeg failed for {$label}: " . implode("\n", $output));
            }

            $playlistPaths[$label] = "hls/{$videoId}/{$label}/index.m3u8";
        }

        // حذف فایل mp4 موقت
        unlink($tempPath);

        // آپلود همه فایل‌ها به S3
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

        // ایجاد master playlist
        $masterContent = "#EXTM3U\n#EXT-X-VERSION:3\n";
        foreach ($this->qualities as $label => $q) {
            $bandwidth = (int) rtrim($q['bitrate'], 'k') * 1000;
            $masterContent .= "#EXT-X-STREAM-INF:BANDWIDTH={$bandwidth},RESOLUTION={$q['width']}x{$q['height']}\n";
            $masterContent .= "{$label}/index.m3u8\n";
        }

        $masterPathLocal = "{$outputDir}/master.m3u8";
        file_put_contents($masterPathLocal, $masterContent);

        // آپلود master playlist
        $this->s3Service->upload(new File($masterPathLocal), "hls/{$videoId}", "master.m3u8");

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
