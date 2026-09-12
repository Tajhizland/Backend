<?php

namespace App\Enums;

enum DeviceType: string
{
    case Mobile = 'mobile';
    case Tablet = 'tablet';
    case Desktop = 'desktop';
    case Bot = 'bot';
    case Unknown = 'unknown';

    public function label(): string
    {
        return match ($this) {
            static::Mobile => 'موبایل',
            static::Tablet => 'تبلت',
            static::Desktop => 'دسکتاپ',
            static::Bot => 'ربات',
            static::Unknown => 'نامشخص',
        };
    }

    /** ترتیب ثابت نمایش در گزارش‌ها تا رنگ هر دستگاه در همه‌ی نمودارها یکی بماند. */
    public static function reportOrder(): array
    {
        return [static::Mobile, static::Desktop, static::Tablet, static::Bot, static::Unknown];
    }
}
