<?php

namespace App\Services\ConvertToHLS;

use Illuminate\Process\Exceptions\ProcessFailedException;
use Symfony\Component\Process\Process;

class ConvertToHLSService implements ConvertToHLSServiceInterface
{

    public function convert($videoPath)
    {
        $outputPath = storage_path('app/videos/hls/');
        $fileName = pathinfo($videoPath, PATHINFO_FILENAME);

        // ایجاد پوشه اگر وجود ندارد
        if (!is_dir($outputPath)) {
            mkdir($outputPath, 0777, true);
        }

        // دستور ffmpeg برای تبدیل
        $process = new Process([
            'ffmpeg',
            '-i', $videoPath,
            '-preset', 'ultrafast',
            '-g', '48',
            '-sc_threshold', '0',
            '-hls_time', '10',
            '-hls_playlist_type', 'vod',
            '-hls_segment_filename', "$outputPath/{$fileName}_%03d.ts",
            "$outputPath/{$fileName}.m3u8"
        ]);
        $process->run();

        // اگر دستور شکست خورد
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return "$fileName.m3u8";
    }
}
