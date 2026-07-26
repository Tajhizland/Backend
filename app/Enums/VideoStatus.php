<?php

namespace App\Enums;

enum VideoStatus: string
{
    /** فایل روی S3 نشسته، منتظر شروع ترنسکد */
    case Queued = 'queued';

    /** ffmpeg در حال ساخت نسخه‌های HLS است */
    case Processing = 'processing';

    /** HLS آماده و قابل پخش است */
    case Ready = 'ready';

    /** ترنسکد شکست خورد؛ جزئیات در لاگ و در ستون video_error */
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            static::Queued => 'در صف پردازش',
            static::Processing => 'در حال پردازش',
            static::Ready => 'آماده',
            static::Failed => 'ناموفق',
        };
    }
}
