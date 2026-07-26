<?php

namespace App\Jobs;

use App\Enums\VideoStatus;
use App\Models\Vlog;
use App\Services\S3\S3ServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;

class ConvertVideoToHlsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 7200;

    /** ترنسکد گران است؛ تلاش مجدد خودکار فقط منابع را می‌سوزاند */
    public $tries = 1;

    private Vlog $vlog;

    /**
     * ترتیب از کم‌کیفیت به پرکیفیت. کیفیت بالاتر از رزولوشن منبع ساخته
     * نمی‌شود تا وقت CPU صرف بزرگ‌نمایی بی‌فایده نشود.
     */
    private array $qualities = [
        '240p' => ['height' => 240, 'width' => 426, 'bitrate' => '400k', 'audio' => '64k'],
        '360p' => ['height' => 360, 'width' => 640, 'bitrate' => '800k', 'audio' => '96k'],
        '480p' => ['height' => 480, 'width' => 854, 'bitrate' => '1000k', 'audio' => '128k'],
        '720p' => ['height' => 720, 'width' => 1280, 'bitrate' => '1400k', 'audio' => '128k'],
    ];

    public function __construct(Vlog $vlog)
    {
        $this->vlog = $vlog;
    }

    public function handle(S3ServiceInterface $s3Service): void
    {
        $videoId = Str::uuid()->toString();
        $tempPath = storage_path("app/temp_videos/{$videoId}.mp4");
        $outputDir = storage_path("app/hls_temp/{$videoId}");
        $previousHls = $this->vlog->hls;

        $this->vlog->update([
            'video_status' => VideoStatus::Processing->value,
            'video_error' => null,
        ]);

        try {
            $this->ensureDirectory(dirname($tempPath));
            $this->ensureDirectory($outputDir);

            $s3Service->download("vlog/" . $this->vlog->video, $tempPath);

            $qualities = $this->applicableQualities($tempPath);

            foreach (array_keys($qualities) as $label) {
                $this->ensureDirectory("{$outputDir}/{$label}");
            }

            // یک بار دیکد، همه‌ی کیفیت‌ها با هم — به‌جای چهار اجرای جداگانه
            $this->transcode($tempPath, $outputDir, $qualities);

            @unlink($tempPath);

            $this->writeMasterPlaylist($outputDir, $qualities);
            $this->uploadDirectory($s3Service, $outputDir, "hls/{$videoId}");

            $this->vlog->update([
                'hls' => "{$videoId}/master.m3u8",
                'video_status' => VideoStatus::Ready->value,
                'video_error' => null,
            ]);

            // نسخه‌ی قبلی فقط بعد از موفقیت پاک می‌شود تا در صورت شکست،
            // ویدیوی سالم قبلی از دسترس خارج نشود
            if ($previousHls) {
                try {
                    $s3Service->removeFolder("hls/" . dirname($previousHls));
                } catch (\Throwable $e) {
                    Log::warning("removing old hls failed for vlog {$this->vlog->id}: {$e->getMessage()}");
                }
            }
        } finally {
            @unlink($tempPath);
            $this->deleteDirectory($outputDir);
        }
    }

    public function failed(?\Throwable $exception): void
    {
        $this->vlog->update([
            'video_status' => VideoStatus::Failed->value,
            'video_error' => Str::limit((string)$exception?->getMessage(), 1000),
        ]);

        Log::error("HLS conversion failed for vlog {$this->vlog->id}: " . $exception?->getMessage());
    }

    /* ------------------------------------------------------------------ */

    private function transcode(string $input, string $outputDir, array $qualities): void
    {
        $hasAudio = $this->hasAudio($input);

        $labels = array_keys($qualities);
        $count = count($labels);
        $indexes = range(0, $count - 1);

        // یک split برای همه‌ی خروجی‌ها، تا ورودی فقط یک بار دیکد شود
        $filter = "[0:v]split={$count}" . implode('', array_map(fn($i) => "[s{$i}]", $indexes)) . ';';
        $filter .= implode(';', array_map(
            fn($i) => "[s{$i}]scale=-2:{$qualities[$labels[$i]]['height']}[v{$i}]",
            $indexes
        ));

        $command = ['ffmpeg', '-y', '-i', $input, '-filter_complex', $filter];
        $streamMap = [];

        foreach ($labels as $i => $label) {
            $q = $qualities[$label];
            $bitrate = (int)rtrim($q['bitrate'], 'k');

            array_push($command,
                '-map', "[v{$i}]",
                "-c:v:{$i}", 'libx264',
                "-b:v:{$i}", $q['bitrate'],
                "-maxrate:v:{$i}", $q['bitrate'],
                "-bufsize:v:{$i}", ($bitrate * 2) . 'k',
            );

            if ($hasAudio) {
                array_push($command, '-map', 'a:0', "-c:a:{$i}", 'aac', "-b:a:{$i}", $q['audio']);
                $streamMap[] = "v:{$i},a:{$i},name:{$label}";
            } else {
                $streamMap[] = "v:{$i},name:{$label}";
            }
        }

        array_push($command,
            '-preset', 'veryfast',
            '-profile:v', 'main',
            '-sc_threshold', '0',
            '-g', '48',
            '-keyint_min', '48',
            '-ar', '48000',
            '-f', 'hls',
            '-hls_time', '6',
            '-hls_playlist_type', 'vod',
            '-hls_flags', 'independent_segments',
            '-hls_segment_filename', "{$outputDir}/%v/segment_%03d.ts",
            '-var_stream_map', implode(' ', $streamMap),
            "{$outputDir}/%v/index.m3u8",
        );

        $result = Process::timeout($this->timeout)->run($command);

        if (!$result->successful())
            throw new \RuntimeException("FFmpeg failed: " . Str::limit($result->errorOutput(), 2000));
    }

    /** فقط کیفیت‌هایی که از رزولوشن منبع بالاتر نیستند */
    private function applicableQualities(string $input): array
    {
        $probe = $this->probe($input, 'stream=height', 'v:0');

        // اگر ffprobe جواب نداد، مثل قبل همه‌ی کیفیت‌ها ساخته می‌شوند
        if (!$probe['ok']) return $this->qualities;

        $height = (int)$probe['value'];
        if ($height <= 0) return $this->qualities;

        $applicable = array_filter($this->qualities, fn($q) => $q['height'] <= $height);

        // اگر منبع از پایین‌ترین کیفیت هم کوچک‌تر بود، همان پایین‌ترین ساخته می‌شود
        return empty($applicable) ? array_slice($this->qualities, 0, 1, true) : $applicable;
    }

    private function hasAudio(string $input): bool
    {
        $probe = $this->probe($input, 'stream=codec_type', 'a:0');

        // اگر ffprobe در دسترس نبود نباید نتیجه بگیریم «صدا ندارد» — آن‌وقت
        // صدای همه‌ی ویدیوها بی‌سروصدا حذف می‌شد. پیش‌فرض امن: صدا دارد.
        if (!$probe['ok']) return true;

        return $probe['value'] !== null;
    }

    /** @return array{ok: bool, value: ?string} */
    private function probe(string $input, string $entries, string $stream): array
    {
        try {
            $result = Process::timeout(120)->run([
                'ffprobe', '-v', 'error',
                '-select_streams', $stream,
                '-show_entries', $entries,
                '-of', 'default=nw=1:nk=1',
                $input,
            ]);
        } catch (\Throwable $e) {
            Log::warning("ffprobe unavailable: {$e->getMessage()}");
            return ['ok' => false, 'value' => null];
        }

        if (!$result->successful()) return ['ok' => false, 'value' => null];

        $value = trim($result->output());

        return ['ok' => true, 'value' => $value === '' ? null : $value];
    }

    private function writeMasterPlaylist(string $outputDir, array $qualities): void
    {
        $content = "#EXTM3U\n#EXT-X-VERSION:3\n";

        foreach ($qualities as $label => $q) {
            $bandwidth = ((int)rtrim($q['bitrate'], 'k') + (int)rtrim($q['audio'], 'k')) * 1000;
            $content .= "#EXT-X-STREAM-INF:BANDWIDTH={$bandwidth},RESOLUTION={$q['width']}x{$q['height']}\n";
            $content .= "{$label}/index.m3u8\n";
        }

        file_put_contents("{$outputDir}/master.m3u8", $content);
    }

    private function uploadDirectory(S3ServiceInterface $s3Service, string $outputDir, string $prefix): void
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($outputDir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $fileInfo) {
            if (!$fileInfo->isFile()) continue;

            $localFile = $fileInfo->getPathname();
            $relativePath = str_replace($outputDir . '/', '', $localFile);

            $s3Service->uploadStream($localFile, "{$prefix}/{$relativePath}", $this->mimeFor($relativePath));
        }
    }

    private function mimeFor(string $path): string
    {
        return match (strtolower((string)pathinfo($path, PATHINFO_EXTENSION))) {
            'm3u8' => 'application/vnd.apple.mpegurl',
            'ts' => 'video/mp2t',
            default => 'application/octet-stream',
        };
    }

    private function ensureDirectory(string $dir): void
    {
        if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir))
            throw new \RuntimeException("Unable to create directory: {$dir}");
    }

    private function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) return;

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = "$dir/$file";
            is_dir($path) ? $this->deleteDirectory($path) : @unlink($path);
        }

        @rmdir($dir);
    }
}
