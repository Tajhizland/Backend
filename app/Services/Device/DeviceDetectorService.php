<?php

namespace App\Services\Device;

use App\Enums\DeviceType;

/**
 * تشخیص نوع دستگاه، سیستم‌عامل و مرورگر از روی User-Agent.
 *
 * عمدا بدون وابستگی بیرونی نوشته شده: برای گزارش «موبایل یا دسکتاپ» همین دقت کافی است
 * و یک پکیج کامل، دیتابیس چندمگابایتی و به‌روزرسانی مداوم می‌خواهد.
 *
 * ترتیب بررسی‌ها مهم است و نباید جابه‌جا شود:
 *  - ربات قبل از همه، چون بعضی خزنده‌ها خودشان را موبایل معرفی می‌کنند.
 *  - تبلت قبل از موبایل، چون UA اندروید تبلت هم کلمه‌ی android دارد.
 *  - Edge/Opera/Samsung قبل از Chrome و Chrome قبل از Safari، چون UA هرکدام نام قبلی را در خود دارد.
 */
class DeviceDetectorService implements DeviceDetectorServiceInterface
{
    private const BOT = '/bot|crawl|spider|slurp|mediapartners|facebookexternalhit|whatsapp|telegrambot|headlesschrome|lighthouse|ahrefs|semrush|petalbot|bingpreview|duckduckgo|applebot|pingdom|uptime/i';

    private const TABLET = '/ipad|tablet|playbook|silk|kindle|nexus (7|9|10)/i';

    private const MOBILE = '/mobile|iphone|ipod|android|blackberry|bb10|windows phone|iemobile|opera mini|webos|palm|symbian/i';

    private const PLATFORMS = [
        'iPadOS' => '/ipad/i',
        'iOS' => '/iphone|ipod/i',
        'Android' => '/android/i',
        'Windows' => '/windows nt|win64|win32/i',
        'macOS' => '/macintosh|mac os x/i',
        'ChromeOS' => '/cros/i',
        'Ubuntu' => '/ubuntu/i',
        'Linux' => '/linux|x11/i',
    ];

    private const BROWSERS = [
        'Edge' => '/edg(e|a|ios)?\//i',
        'Opera' => '/opr\/|opera|opios/i',
        'Samsung Internet' => '/samsungbrowser/i',
        'UC Browser' => '/ucbrowser/i',
        'Firefox' => '/firefox|fxios/i',
        'Chrome' => '/chrome|crios|chromium/i',
        'Safari' => '/safari/i',
        'Internet Explorer' => '/msie|trident/i',
    ];

    public function detect(?string $userAgent): array
    {
        $userAgent = trim((string)$userAgent);

        if ($userAgent === '') {
            return ['device' => DeviceType::Unknown->value, 'platform' => null, 'browser' => null];
        }

        return [
            'device' => $this->deviceType($userAgent),
            'platform' => $this->matchFirst(self::PLATFORMS, $userAgent),
            'browser' => $this->matchFirst(self::BROWSERS, $userAgent),
        ];
    }

    private function deviceType(string $userAgent): string
    {
        return match (true) {
            (bool)preg_match(self::BOT, $userAgent) => DeviceType::Bot->value,
            (bool)preg_match(self::TABLET, $userAgent) => DeviceType::Tablet->value,
            // اندرویدِ بدون کلمه‌ی mobile تبلت است؛ قرارداد خود گوگل برای سازندگان مرورگر.
            (bool)preg_match('/android/i', $userAgent) && !preg_match('/mobile/i', $userAgent) => DeviceType::Tablet->value,
            (bool)preg_match(self::MOBILE, $userAgent) => DeviceType::Mobile->value,
            default => DeviceType::Desktop->value,
        };
    }

    private function matchFirst(array $patterns, string $userAgent): ?string
    {
        foreach ($patterns as $name => $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return $name;
            }
        }

        return null;
    }
}
