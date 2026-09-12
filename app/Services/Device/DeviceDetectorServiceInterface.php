<?php

namespace App\Services\Device;

interface DeviceDetectorServiceInterface
{
    /**
     * @return array{device: string, platform: string|null, browser: string|null}
     */
    public function detect(?string $userAgent): array;
}
