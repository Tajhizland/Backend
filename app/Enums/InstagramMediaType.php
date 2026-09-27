<?php

namespace App\Enums;

enum InstagramMediaType: string
{
    case Image = 'image';
    case Video = 'video';

    /** نوع رسانه از روی mime فایل آپلودی؛ اعتبارسنجی mime در FormRequest انجام شده است */
    public static function fromMime(?string $mime): self
    {
        return str_starts_with((string)$mime, 'video/') ? self::Video : self::Image;
    }

    public function label(): string
    {
        return match ($this) {
            static::Image => 'تصویر',
            static::Video => 'ویدیو',
        };
    }
}
