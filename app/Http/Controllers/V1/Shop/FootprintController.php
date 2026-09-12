<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\FootprintRequest;
use App\Models\Footprint;
use App\Services\Device\DeviceDetectorServiceInterface;

class FootprintController extends Controller
{
    public function __construct(
        private readonly DeviceDetectorServiceInterface $deviceDetectorService
    )
    {
    }

    public function handle(FootprintRequest $request)
    {
        $data = $request->validated();
        $agent = $this->deviceDetectorService->detect($request->userAgent());

        Footprint::create([
            "page" => $data["path"],
            "ip" => $request->ip(),
            "user_id" => $data["user_id"] ?? null,
            "device" => $agent["device"],
            "platform" => $agent["platform"],
            "browser" => $agent["browser"],
        ]);
    }
}
